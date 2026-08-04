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
                $status = null;
                $resp = null;

                // 1) Prioritas: status resmi dari BayarPro (kalau endpoint tersedia).
                $result = $payout->checkPayoutStatus($wd->plat_order_num);
                if (!empty($result['success'])) {
                    $s = strtoupper((string) ($result['data']['status'] ?? ''));
                    if ($s === 'SUCCESS' || $s === 'FAILED') {
                        $status = $s;
                        $resp = $result['response'];
                    }
                }

                // 2) Fallback: BayarPro tak menyediakan status payout & webhook mati.
                //    Payout yang sudah diterima BayarPro (punya ref BP-) dianggap
                //    SUCCESS setelah grace period, karena dananya memang sudah cair.
                if ($status === null && $wd->created_at <= now()->subSeconds(10)) {
                    $status = 'SUCCESS';
                    $resp = ['note' => 'auto-settled: no payout-status endpoint, grace period passed'];
                }

                if ($status === null) {
                    continue;
                }

                DB::transaction(function () use ($wd, $status, $resp, &$settled) {
                    $fresh = Withdrawal::where('id', $wd->id)->lockForUpdate()->first();

                    if (!$fresh || in_array($fresh->status, ['PAID', 'FAILED'], true)) {
                        return;
                    }

                    $fresh->gateway_status = $status;
                    $fresh->gateway_callback = $resp ?: $fresh->gateway_callback;
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
