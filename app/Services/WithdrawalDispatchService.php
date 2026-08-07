<?php

namespace App\Services;

use App\Models\Withdrawal;
use App\Services\BankPay\BankPayPayoutService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use RuntimeException;

/**
 * Satu-satunya tempat permintaan pencairan dilepas ke gateway.
 *
 * Dipakai bersama oleh dua jalur yang berbeda - user mengajukan (saat
 * persetujuan admin dimatikan) dan admin menekan Approve - supaya keduanya
 * tidak mungkin punya perlakuan berbeda terhadap uang.
 *
 * Titik ini adalah GARIS SATU ARAH: sebelum dipanggil, penarikan masih bisa
 * dibatalkan dengan aman; sesudahnya tidak ada lagi yang bisa menariknya
 * kembali, dan hanya gateway yang boleh memutuskan berhasil atau gagal.
 */
class WithdrawalDispatchService
{
    public function __construct(
        private BankPayPayoutService $bankPay
    ) {}

    /**
     * Kirim ke gateway. Kalau gagal terkirim, saldo dikembalikan seketika
     * supaya dana user tidak menggantung.
     *
     * @throws RuntimeException berisi pesan yang layak ditunjukkan ke pemanggil
     */
    public function send(Withdrawal $withdrawal): Withdrawal
    {
        if (!in_array($withdrawal->status, ['PENDING', 'APPROVED'], true)) {
            throw new RuntimeException('Penarikan ini tidak dalam status yang bisa dikirim ke gateway.');
        }

        try {
            $result = $this->bankPay->createPayout($withdrawal->fresh(['user', 'payoutAccount']));
        } catch (\Throwable $e) {
            report($e);

            $this->kembalikanSaldo($withdrawal, $e->getMessage());

            throw new RuntimeException($e->getMessage(), 0, $e);
        }

        $withdrawal->update([
            // Diterima gateway, belum tentu cair. Status akhir datang dari
            // notifikasi server atau poller.
            'status' => 'PROCESSING',
            'plat_order_num' => $result['transaction_id'],
            'gateway_status' => 'WAIT PAY',
            'gateway_message' => 'Diterima gateway',
            'gateway_response' => $result['response'],
            'processing_at' => now(),
        ]);

        Log::info('Withdrawal dikirim ke gateway', [
            'withdrawal_id' => $withdrawal->id,
            'order_id' => $withdrawal->order_id,
        ]);

        return $withdrawal->fresh('payoutAccount');
    }

    /**
     * Gagal TERKIRIM berbeda dari gagal DICAIRKAN: di sini gateway belum
     * memegang apa pun, jadi mengembalikan saldo sepenuhnya aman.
     */
    private function kembalikanSaldo(Withdrawal $withdrawal, string $alasan): void
    {
        DB::transaction(function () use ($withdrawal, $alasan) {
            $row = Withdrawal::where('id', $withdrawal->id)
                ->lockForUpdate()
                ->firstOrFail();

            // Kalau statusnya sudah bergerak, jalur lain yang memegang saldonya.
            if (!in_array($row->status, ['PENDING', 'APPROVED'], true)) {
                return;
            }

            $user = $row->user()->lockForUpdate()->firstOrFail();

            $user->saldo_penarikan = (float) $user->saldo_penarikan + (float) $row->amount;
            $user->saldo_hold = max(0, (float) $user->saldo_hold - (float) $row->amount);
            $user->save();

            $row->update([
                'status' => 'FAILED',
                'gateway_status' => 'REQUEST_FAILED',
                'gateway_message' => $alasan,
                'failed_reason' => $alasan,
                'failed_at' => now(),
            ]);
        });
    }
}
