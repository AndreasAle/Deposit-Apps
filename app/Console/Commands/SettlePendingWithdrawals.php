<?php

namespace App\Console\Commands;

use App\Models\Withdrawal;
use App\Services\BankPay\BankPayPayoutService;
use App\Services\WithdrawalSettlementService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

/**
 * Poller cadangan pencairan: kalau notifikasi server tidak sampai, status
 * penarikan yang masih diproses ditanyakan ulang ke BankPay lewat endpoint
 * dfquery.
 *
 * SUCCESS -> PAID (hold dilepas), REFUSE -> FAILED (dana dikembalikan).
 * Status "WAIT PAY" dibiarkan apa adanya sampai gateway memberi keputusan;
 * penarikan TIDAK pernah dianggap sukses hanya karena sudah lama menunggu —
 * itu satu-satunya cara memastikan saldo tidak dihapus untuk dana yang
 * sebenarnya tidak pernah cair. Kalau gateway macet, admin bisa memutuskan
 * manual lewat tombol Set PAID / Set FAILED.
 */
class SettlePendingWithdrawals extends Command
{
    protected $signature = 'withdrawals:settle-pending';

    protected $description = 'Cek status pencairan yang masih diproses ke BankPay lalu selesaikan (cadangan notifikasi).';

    public function handle(BankPayPayoutService $bankPay, WithdrawalSettlementService $settlement): int
    {
        if (!$bankPay->isConfigured()) {
            return self::SUCCESS;
        }

        $rows = Withdrawal::whereIn('status', ['PROCESSING', 'APPROVED'])
            ->where('created_at', '>=', now()->subDays(3))
            ->orderBy('id')
            ->limit(100)
            ->get();

        $settled = 0;

        foreach ($rows as $withdrawal) {
            try {
                $result = $bankPay->queryPayout($withdrawal->order_id);

                if (empty($result['success'])) {
                    continue;
                }

                $changed = match ($result['state']) {
                    'SUCCESS' => $settlement->markPaid($withdrawal->id, $result['response'], 'poller'),
                    'REFUSE' => $settlement->markFailed(
                        $withdrawal->id,
                        $result['message'] ?: 'Pencairan ditolak gateway',
                        $result['response'],
                        'poller'
                    ),
                    // "WAIT PAY" atau status lain: belum ada keputusan.
                    default => false,
                };

                if ($changed) {
                    $settled++;
                }
            } catch (\Throwable $e) {
                Log::error('Withdrawal poller error', [
                    'withdrawal_id' => $withdrawal->id,
                    'message' => $e->getMessage(),
                ]);
            }
        }

        if ($settled > 0) {
            $this->info("Settled {$settled} withdrawal(s).");
        }

        return self::SUCCESS;
    }
}
