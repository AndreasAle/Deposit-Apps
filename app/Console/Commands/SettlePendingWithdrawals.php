<?php

namespace App\Console\Commands;

use App\Models\Withdrawal;
use App\Services\BayarProPayoutService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Poller cadangan payout: karena webhook payout BayarPro tidak andal, tiap
 * menit kita cek status payout yang masih PROCESSING. Kalau SUCCESS -> PAID
 * (lepas hold), kalau FAILED -> refund saldo penarikan. Best-effort: kalau
 * BayarPro tidak menyediakan endpoint status payout, withdrawal dibiarkan
 * PROCESSING untuk dikonfirmasi admin (tombol Set PAID).
 */
class SettlePendingWithdrawals extends Command
{
    protected $signature = 'withdrawals:settle-pending';

    protected $description = 'Cek status payout PROCESSING ke BayarPro lalu selesaikan (fallback webhook).';

    public function handle(BayarProPayoutService $payout): int
    {
        $rows = Withdrawal::where('status', 'PROCESSING')
            ->whereNotNull('plat_order_num')
            ->where('plat_order_num', 'like', 'BP-%') // hanya yang punya reference BayarPro asli
            ->where('created_at', '>=', now()->subDays(3))
            ->orderBy('id')
            ->limit(100)
            ->get();

        $settled = 0;

        foreach ($rows as $wd) {
            try {
                $result = $payout->checkPayoutStatus($wd->plat_order_num);

                if (empty($result['success'])) {
                    continue; // endpoint tak tersedia / belum final
                }

                $status = strtoupper((string) ($result['data']['status'] ?? ''));

                if ($status !== 'SUCCESS' && $status !== 'FAILED') {
                    continue;
                }

                DB::transaction(function () use ($wd, $status, $result, &$settled) {
                    $fresh = Withdrawal::where('id', $wd->id)->lockForUpdate()->first();

                    if (!$fresh || in_array($fresh->status, ['PAID', 'FAILED'], true)) {
                        return;
                    }

                    $fresh->gateway_status = $status;
                    $fresh->gateway_callback = $result['response'] ?: $fresh->gateway_callback;
                    $user = $fresh->user()->lockForUpdate()->firstOrFail();

                    if ($status === 'SUCCESS') {
                        // Dana keluar: lepas hold.
                        $user->saldo_hold = max(0, (float) $user->saldo_hold - (float) $fresh->amount);
                        $user->save();

                        $fresh->status = 'PAID';
                        $fresh->paid_at = now();
                        $fresh->save();
                    } else {
                        // Gagal: kembalikan saldo penarikan dari hold.
                        $user->saldo_penarikan = (float) $user->saldo_penarikan + (float) $fresh->amount;
                        $user->saldo_hold = max(0, (float) $user->saldo_hold - (float) $fresh->amount);
                        $user->save();

                        $fresh->status = 'FAILED';
                        $fresh->failed_reason = 'Payout gagal dari gateway';
                        $fresh->failed_at = now();
                        $fresh->save();
                    }

                    $settled++;

                    Log::info('Withdrawal settled via cron poller', [
                        'order_id' => $fresh->order_id,
                        'plat_order_num' => $fresh->plat_order_num,
                        'status' => $status,
                    ]);
                });
            } catch (\Throwable $e) {
                Log::error('Withdrawal poller error', [
                    'withdrawal_id' => $wd->id,
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
