<?php

namespace App\Services;

use App\Models\Deposit;
use App\Models\Mutation;
use App\Services\DepositChannels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Menerima notifikasi "uang masuk" dari HP listener dan mencocokkannya ke
 * deposit yang menunggu bayar.
 *
 * Jaminan:
 *   1. Idempoten - kiriman ulang notifikasi yang sama tidak pernah menambah
 *      saldo dua kali.
 *   2. Tidak ada notifikasi yang dibuang diam-diam. Yang tidak terbaca atau
 *      tidak ketemu depositnya tetap tersimpan untuk direview admin.
 *   3. Nominal tidak mungkin tertukar antar deposit (dijamin unique index).
 */
class MutationMatcher
{
    /** Kata kunci yang menandakan UANG MASUK. */
    private const CREDIT_HINTS = [
        'menerima', 'diterima', 'terima', 'masuk', 'kredit', 'credit',
        'pembayaran', 'berhasil', 'sukses', 'settlement', 'qris',
    ];

    /** Kata kunci uang KELUAR - jelas bukan pembayaran masuk. */
    private const DEBIT_HINTS = [
        'transfer ke', 'pembelian', 'pembayaran ke', 'top up ke', 'penarikan',
        'tarik tunai', 'debit', 'berkurang', 'terpotong',
    ];

    public function __construct(
        private DepositCreditService $credit
    ) {}

    /**
     * @param  array{ext_id:string, amount?:int|null, raw?:string|null, source?:string|null, device?:string|null, notified_at?:string|null}  $input
     * @return array{duplicate:bool, mutation:Mutation}
     */
    public function handle(array $input): array
    {
        $rawText = $input['raw'] ?? null;
        $amount = $input['amount'] ?? null;
        $note = null;

        if ($amount === null && $rawText) {
            [$amount, $note] = $this->parse($rawText);
        }

        $fingerprint = substr(hash('sha256', ($amount ?? 'null') . '|' . trim((string) $rawText)), 0, 32);

        return DB::transaction(function () use ($input, $amount, $rawText, $note, $fingerprint) {
            $extId = $this->resolveExtId($input['ext_id'], $fingerprint, $duplicate);

            if ($duplicate instanceof Mutation) {
                return ['duplicate' => true, 'mutation' => $duplicate];
            }

            $this->expireStale();

            $status = 'unmatched';
            $depositId = null;
            $candidateId = null;

            if ($amount === null) {
                $status = 'needs_review';
                $note = $note ?: 'nominal tidak diketahui';
            } else {
                $hit = $this->qrisDeposits()
                    ->where('status', 'UNPAID')
                    ->where('real_amount', $amount)
                    ->lockForUpdate()
                    ->first();

                if ($hit) {
                    $status = 'matched';
                    $depositId = $hit->id;
                } else {
                    $grace = (int) config('deposit.qris.late_grace_minutes', 60);

                    $late = $this->qrisDeposits()
                        ->whereIn('status', ['EXPIRED', 'FAILED'])
                        ->where('real_amount', $amount)
                        ->where('expired_at', '>', now()->subMinutes($grace))
                        ->latest('id')
                        ->first();

                    if ($late) {
                        $status = 'needs_review';
                        $candidateId = $late->id;
                        $note = 'notifikasi telat - invoice sudah kedaluwarsa, cek manual';
                    } else {
                        $note = $note ?: 'tidak ada deposit UNPAID dengan nominal ini';
                    }
                }
            }

            $mutation = Mutation::create([
                'ext_id' => $extId,
                'fingerprint' => $fingerprint,
                'amount' => $amount,
                'raw' => $rawText,
                'source' => $input['source'] ?? 'listener',
                'device' => $input['device'] ?? null,
                'status' => $status,
                'deposit_id' => $depositId,
                'candidate_deposit_id' => $candidateId,
                'note' => $note,
                'notified_at' => $input['notified_at'] ?? null,
            ]);

            if ($status === 'matched') {
                $this->settle($depositId, $mutation);
            }

            return ['duplicate' => false, 'mutation' => $mutation->fresh()];
        });
    }

    /**
     * Bedakan "kiriman ulang" dari "dua pembayaran yang kebetulan ext_id-nya kembar".
     *
     * DANA sering memakai notification_id yang sama berulang kali. Kalau
     * beberapa orang bayar di detik yang sama, MacroDroid bisa menghasilkan
     * ext_id identik. Kalau hanya ext_id yang dicek, pembayaran ke-2/3/4 akan
     * tertelan sebagai "duplikat" - fitur anti-dobel justru menghilangkan uang.
     *
     * Maka: ext_id kembar TAPI isi notif beda = notifikasi BERBEDA.
     */
    private function resolveExtId(string $baseExtId, string $fingerprint, &$duplicate): string
    {
        $duplicate = null;
        $extId = $baseExtId;

        for ($attempt = 0; $attempt < 50; $attempt++) {
            $prev = Mutation::where('ext_id', $extId)->lockForUpdate()->first();

            if (!$prev) {
                return $extId;
            }

            if ($prev->fingerprint === $fingerprint) {
                $duplicate = $prev; // isinya identik -> memang kiriman ulang
                return $extId;
            }

            $extId = $baseExtId . '#' . $fingerprint . ($attempt ? '-' . $attempt : '');
        }

        throw new \RuntimeException('Tidak bisa membuat ext_id unik untuk mutasi ini.');
    }

    /**
     * Hanya deposit QRIS statis yang boleh dicocokkan dengan notifikasi HP.
     *
     * Deposit saluran gateway punya `real_amount` = nominal bulat yang diminta,
     * sehingga gampang kebetulan sama dengan mutasi masuk. Tanpa penyaringan
     * ini, satu notifikasi bisa melunasi invoice gateway milik orang lain.
     * Penanda yang dipakai adalah `unique_code`, sama seperti cakupan unique
     * index nominal unik, jadi deposit warisan pun ikut tersaring dengan benar.
     */
    private function qrisDeposits()
    {
        return Deposit::query()
            ->where('payment_channel', DepositChannels::QRIS_STATIS)
            ->whereNotNull('unique_code');
    }

    private function settle(int $depositId, Mutation $mutation): void
    {
        $deposit = Deposit::lockForUpdate()->find($depositId);

        if (!$deposit || $deposit->status === 'PAID') {
            return;
        }

        $deposit->status = 'PAID';
        $deposit->paid_at = now();
        $deposit->plat_order_num = $deposit->plat_order_num ?: ('LISTENER-' . $mutation->id);
        $deposit->save();

        $credited = $this->credit->credit($deposit);

        Log::info('Deposit QRIS lunas via listener', [
            'deposit_id' => $deposit->id,
            'order_id' => $deposit->order_id,
            'mutation_id' => $mutation->id,
            'paid' => (float) $deposit->real_amount,
            'credited' => $credited,
        ]);
    }

    /**
     * Lepas nominal unik milik invoice yang sudah lewat waktu, supaya bisa
     * dipakai deposit berikutnya.
     */
    public function expireStale(): int
    {
        return Deposit::where('status', 'UNPAID')
            ->whereNotNull('expired_at')
            ->where('expired_at', '<', now())
            ->update(['status' => 'EXPIRED']);
    }

    /**
     * Baca nominal dari teks notifikasi.
     *
     * @return array{0:int|null, 1:string|null}  [nominal, alasan kalau gagal]
     */
    public function parse(?string $raw): array
    {
        if (!$raw || trim($raw) === '') {
            return [null, 'notifikasi kosong'];
        }

        $low = mb_strtolower($raw);

        foreach (self::DEBIT_HINTS as $bad) {
            if (str_contains($low, $bad)) {
                return [null, "terlihat sebagai uang keluar ('{$bad}')"];
            }
        }

        if (!preg_match_all('/(?:rp|idr)\s*\.?\s*(\d[\d.,]*)/i', $raw, $m)) {
            return [null, 'nominal tidak ditemukan di teks notifikasi'];
        }

        $amounts = array_values(array_unique(array_filter(
            array_map([$this, 'toRupiah'], $m[1])
        )));

        if (empty($amounts)) {
            return [null, 'nominal tidak terbaca'];
        }

        if (count($amounts) > 1) {
            sort($amounts);
            return [null, 'ada beberapa nominal: ' . implode(', ', $amounts) . ' - pastikan manual'];
        }

        foreach (self::CREDIT_HINTS as $hint) {
            if (str_contains($low, $hint)) {
                return [$amounts[0], null];
            }
        }

        return [null, 'tidak ada kata kunci uang masuk - pastikan manual'];
    }

    /** '2.000' -> 2000, '2.000,00' -> 2000, '50.123' -> 50123 */
    public function toRupiah(string $s): int
    {
        $s = rtrim(trim($s), '.,');

        // Buang bagian desimal (dua digit di ujung setelah . atau ,)
        if (preg_match('/[.,](\d{2})$/', $s) && strlen(preg_replace('/\D/', '', $s)) > 2) {
            $s = preg_replace('/[.,]\d{2}$/', '', $s);
        }

        $digits = preg_replace('/\D/', '', $s);

        return $digits === '' ? 0 : (int) $digits;
    }
}
