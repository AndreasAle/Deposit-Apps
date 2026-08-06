<?php

namespace App\Services;

use App\Models\Deposit;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Menutup sebuah deposit menjadi PAID lalu mengkreditkan saldo — satu pintu
 * untuk semua jalur konfirmasi gateway (notifikasi server, sinkronisasi saat
 * halaman invoice dibuka, dan poller cron).
 *
 * Idempoten: mengunci baris deposit, dan berhenti kalau statusnya sudah PAID.
 * Aman dipanggil berkali-kali untuk pembayaran yang sama.
 */
class DepositSettlementService
{
    public function __construct(
        private DepositCreditService $credit
    ) {}

    /**
     * @param  float|null  $paidAmount  Nominal riil dari gateway; null = pakai yang tercatat
     * @param  array<string, mixed>  $response  Payload gateway untuk jejak audit
     * @return bool  true kalau deposit ini baru saja dilunasi oleh pemanggil
     */
    public function settle(
        int $depositId,
        ?float $paidAmount = null,
        ?string $transactionId = null,
        array $response = [],
        string $via = 'gateway'
    ): bool {
        return DB::transaction(function () use ($depositId, $paidAmount, $transactionId, $response, $via) {
            $deposit = Deposit::lockForUpdate()->find($depositId);

            if (!$deposit || $deposit->status === 'PAID') {
                return false;
            }

            // Nominal yang benar-benar masuk adalah acuan pencatatan transaksi.
            if ($paidAmount !== null && $paidAmount > 0) {
                $deposit->real_amount = $paidAmount;
            }

            $deposit->status = 'PAID';
            $deposit->paid_at = now();
            $deposit->plat_order_num = $transactionId ?: $deposit->plat_order_num;

            if ($response !== []) {
                $deposit->gateway_response = $response;
            }

            $deposit->save();

            $credited = $this->credit->credit($deposit);

            Log::info('Deposit lunas', [
                'deposit_id' => $deposit->id,
                'order_id' => $deposit->order_id,
                'channel' => $deposit->payment_channel,
                'via' => $via,
                'paid' => (float) $deposit->real_amount,
                'credited' => $credited,
            ]);

            return true;
        });
    }
}
