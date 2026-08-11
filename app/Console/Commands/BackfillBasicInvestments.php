<?php

namespace App\Console\Commands;

use App\Models\UserInvestment;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

/**
 * Memulihkan pembelian produk BASIC yang terlanjur lahir cacat.
 *
 * Sebelum perbaikan, pembelian kategori 1 disimpan dengan durasi 0, profit 0,
 * dan langsung berstatus `finished` - user membayar penuh tapi tidak pernah
 * menerima apa pun. Perintah ini mengembalikan durasi dan profitnya dari
 * pengaturan produk, menghitung ulang tanggal selesai dari tanggal beli, lalu
 * mengaktifkan kembali investasinya supaya profitnya dicairkan oleh
 * investments:settle-profits pada waktunya.
 *
 * MODE AMAN: secara default hanya menampilkan rencana. Tambahkan --apply untuk
 * benar-benar mengubah data, dan cadangkan database lebih dulu.
 */
class BackfillBasicInvestments extends Command
{
    protected $signature = 'investments:backfill-basic
                            {--apply : Benar-benar ubah data (tanpa ini hanya menampilkan rencana)}
                            {--days=60 : Batasi pada pembelian sekian hari terakhir}';

    protected $description = 'Pulihkan durasi & profit pembelian produk BASIC yang terlanjur tersimpan nol.';

    public function handle(): int
    {
        $terapkan = (bool) $this->option('apply');
        $hari = (int) $this->option('days');

        $kandidat = UserInvestment::query()
            ->with(['product:id,category_id,name,duration_days,daily_profit,total_profit', 'user:id,name'])
            // Ciri pembelian yang lahir cacat: selesai seketika, tanpa profit.
            ->where('status', 'finished')
            ->where('duration_days', 0)
            ->where('total_profit', 0)
            ->where('created_at', '>=', now()->subDays($hari))
            ->whereHas('product', function ($q) {
                $q->where('category_id', 1)->where('duration_days', '>', 0);
            })
            ->orderBy('id')
            ->get();

        if ($kandidat->isEmpty()) {
            $this->info('Tidak ada pembelian BASIC yang perlu dipulihkan.');

            return self::SUCCESS;
        }

        $baris = [];
        $totalProfit = 0;

        foreach ($kandidat as $inv) {
            $durasi = (int) $inv->product->duration_days;
            $profit = (int) $inv->product->total_profit;
            $mulai = $inv->start_date ?: $inv->created_at;
            $selesai = $mulai->copy()->addDays($durasi);

            $totalProfit += $profit;

            $baris[] = [
                $inv->id,
                ($inv->user->name ?? '?') . ' (#' . $inv->user_id . ')',
                $inv->product->name,
                number_format((float) $inv->price, 0, ',', '.'),
                $durasi . ' hari',
                number_format($profit, 0, ',', '.'),
                $selesai->format('d/m/Y'),
                $selesai->isPast() ? 'cair segera' : 'menunggu',
            ];
        }

        $this->table(
            ['ID', 'User', 'Produk', 'Modal', 'Durasi', 'Profit', 'Selesai', 'Status'],
            $baris
        );

        $this->newLine();
        $this->info(count($baris) . ' pembelian akan dipulihkan, total profit Rp '
            . number_format($totalProfit, 0, ',', '.'));

        if (!$terapkan) {
            $this->newLine();
            $this->warn('Ini baru rencana. Tidak ada data yang diubah.');
            $this->line('Cadangkan database lebih dulu, lalu ulangi dengan --apply.');

            return self::SUCCESS;
        }

        $dipulihkan = 0;

        foreach ($kandidat as $inv) {
            DB::transaction(function () use ($inv, &$dipulihkan) {
                $baris = UserInvestment::where('id', $inv->id)->lockForUpdate()->first();

                // Periksa ulang di dalam kunci: jangan sentuh yang sudah berubah.
                if (!$baris || $baris->status !== 'finished' || (int) $baris->duration_days !== 0) {
                    return;
                }

                $durasi = (int) $inv->product->duration_days;
                $mulai = $baris->start_date ?: $baris->created_at;

                $baris->duration_days = $durasi;
                $baris->daily_profit = (int) $inv->product->daily_profit;
                $baris->total_profit = (int) $inv->product->total_profit;
                $baris->end_date = $mulai->copy()->addDays($durasi);

                // Diaktifkan kembali supaya profitnya dicairkan pada waktunya
                // oleh investments:settle-profits - bukan dibayar langsung
                // di sini, agar hanya ada satu tempat yang menambah saldo.
                $baris->status = 'active';
                $baris->save();

                $dipulihkan++;
            });
        }

        $this->newLine();
        $this->info("{$dipulihkan} pembelian dipulihkan.");
        $this->line('Profitnya akan masuk ke saldo penarikan saat tanggal selesainya tercapai.');

        return self::SUCCESS;
    }
}
