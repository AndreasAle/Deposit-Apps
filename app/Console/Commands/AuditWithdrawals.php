<?php

namespace App\Console\Commands;

use App\Models\Withdrawal;
use App\Services\BankPay\BankPayPayoutService;
use Illuminate\Console\Command;

/**
 * Mencari penarikan yang saldonya sudah dikembalikan ke user PADAHAL gateway
 * benar-benar mencairkan dananya - artinya user menerima dua kali dan uangnya
 * hilang dari sisi merchant.
 *
 * Sidik jarinya jelas: penarikan yang punya `plat_order_num` berarti pernah
 * dikirim ke gateway. Kalau akhirnya berstatus REJECTED/FAILED/CANCELLED,
 * saldo user sudah dikembalikan - dan itu hanya benar kalau gateway memang
 * TIDAK mencairkannya. Perintah ini menanyakan satu per satu untuk memastikan.
 *
 * HANYA MEMBACA. Tidak mengubah status maupun saldo apa pun; keputusan atas
 * temuannya ada di tangan manusia.
 */
class AuditWithdrawals extends Command
{
    protected $signature = 'withdrawals:audit
                            {--days=90 : Seberapa jauh ke belakang diperiksa}
                            {--sleep=250 : Jeda antar permintaan (milidetik), gateway membatasi 5/detik}';

    protected $description = 'Cocokkan penarikan yang saldonya dikembalikan dengan status sebenarnya di gateway.';

    public function handle(BankPayPayoutService $payout): int
    {
        if (!$payout->isConfigured()) {
            $this->error('BankPay belum dikonfigurasi. Isi BANKPAY_MEMBER_ID dan BANKPAY_KEY.');

            return self::FAILURE;
        }

        $hari = (int) $this->option('days');

        // Yang punya plat_order_num pernah sampai ke gateway. Yang tidak punya
        // gagal sebelum terkirim - itu memang aman dan tidak perlu diperiksa.
        $kandidat = Withdrawal::whereIn('status', ['REJECTED', 'FAILED', 'CANCELLED'])
            ->whereNotNull('plat_order_num')
            ->where('created_at', '>=', now()->subDays($hari))
            ->with('user:id,name,phone')
            ->orderBy('id')
            ->get();

        if ($kandidat->isEmpty()) {
            $this->info('Tidak ada penarikan yang perlu dicocokkan.');

            return self::SUCCESS;
        }

        $this->info("Memeriksa {$kandidat->count()} penarikan ke gateway...");
        $this->newLine();

        $temuan = [];
        $takPasti = [];
        $jeda = max(0, (int) $this->option('sleep')) * 1000;

        $bar = $this->output->createProgressBar($kandidat->count());
        $bar->start();

        foreach ($kandidat as $wd) {
            $cek = $payout->queryPayout($wd->order_id);

            if (empty($cek['success'])) {
                $takPasti[] = [$wd->id, $wd->order_id, $wd->status, 'gateway tidak menjawab'];
            } elseif ($cek['state'] === 'SUCCESS') {
                $temuan[] = [
                    $wd->id,
                    $wd->order_id,
                    ($wd->user->name ?? '?') . ' (#' . $wd->user_id . ')',
                    number_format((float) $wd->amount, 0, ',', '.'),
                    $wd->status,
                    $wd->created_at->format('d/m H:i'),
                ];
            }

            $bar->advance();

            // Antarmuka pencairan dibatasi 5 permintaan/detik.
            if ($jeda > 0) {
                usleep($jeda);
            }
        }

        $bar->finish();
        $this->newLine(2);

        if ($takPasti !== []) {
            $this->warn('Tidak bisa dipastikan (' . count($takPasti) . ') - ulangi nanti:');
            $this->table(['ID', 'Order', 'Status kita', 'Catatan'], $takPasti);
            $this->newLine();
        }

        if ($temuan === []) {
            $this->info('BERSIH. Tidak ada saldo yang dikembalikan untuk dana yang sebenarnya cair.');

            return self::SUCCESS;
        }

        $this->error('DITEMUKAN ' . count($temuan) . ' penarikan yang dananya CAIR di gateway '
            . 'tapi saldonya sudah dikembalikan ke user:');
        $this->table(['ID', 'Order', 'User', 'Nominal', 'Status kita', 'Waktu'], $temuan);

        $total = collect($temuan)->sum(fn ($b) => (float) str_replace('.', '', $b[3]));

        $this->newLine();
        $this->error('Total kelebihan saldo user: Rp ' . number_format($total, 0, ',', '.'));
        $this->line('Perintah ini tidak mengubah apa pun. Putuskan sendiri tindak lanjutnya.');

        return self::SUCCESS;
    }
}
