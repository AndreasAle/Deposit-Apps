<?php

namespace App\Console\Commands;

use App\Models\Deposit;
use App\Services\BankPay\BankPayDepositService;
use App\Services\DepositSettlementService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

/**
 * Poller cadangan untuk saluran gateway: notifikasi server bisa saja tidak
 * sampai (jaringan putus, aplikasi sedang deploy), jadi status deposit yang
 * masih menggantung ditanyakan ulang ke BankPay secara berkala.
 *
 * Hanya menyentuh deposit saluran bankpay. Deposit QRIS statis tidak dikenal
 * gateway dan dikonfirmasi lewat notification listener.
 *
 * Idempoten: penyelesaiannya lewat DepositSettlementService yang mengunci
 * baris dan menolak melunasi deposit yang sudah PAID.
 *
 * Jalur utama konfirmasi tetap notifikasi server yang instan; ini cuma jaring
 * pengaman. Karena itu cakupannya sengaja sempit - hanya invoice yang masih
 * mungkin dibayar - supaya tidak membanjiri gateway dengan pertanyaan soal
 * invoice yang sudah pasti mati.
 */
class SettlePendingDeposits extends Command
{
    protected $signature = 'deposits:settle-pending';

    protected $description = 'Cek status deposit gateway yang masih menggantung lalu kreditkan saldo (cadangan notifikasi).';

    public function handle(BankPayDepositService $bankPay, DepositSettlementService $settlement): int
    {
        if (!$bankPay->isConfigured()) {
            return self::SUCCESS;
        }

        // Batas kelayakan bertanya ada di Deposit::scopeMenungguGateway supaya
        // sama persis dengan yang dipakai halaman invoice: hanya invoice yang
        // masih mungkin dibayar, termasuk kelonggaran untuk pembayaran telat.
        $pending = Deposit::menungguGateway()
            ->where('created_at', '>=', now()->subDay())
            ->orderBy('id')
            ->limit(100)
            ->get();

        $settled = 0;

        foreach ($pending as $deposit) {
            try {
                $result = $bankPay->queryOrder($deposit->order_id);

                if (empty($result['success']) || empty($result['paid'])) {
                    continue;
                }

                $justSettled = $settlement->settle(
                    $deposit->id,
                    $result['amount'],
                    $result['transaction_id'],
                    $result['response'],
                    'poller'
                );

                if ($justSettled) {
                    $settled++;
                }
            } catch (\Throwable $e) {
                Log::error('Deposit poller error', [
                    'deposit_id' => $deposit->id,
                    'message' => $e->getMessage(),
                ]);
            }
        }

        if ($settled > 0) {
            $this->info("Settled {$settled} deposit(s).");
        }

        return self::SUCCESS;
    }
}
