<?php

namespace App\Console\Commands;

use App\Models\Deposit;
use App\Models\User;
use App\Services\BayarProService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Poller cadangan: karena webhook BayarPro tidak andal, tiap menit kita
 * tanya status deposit yang masih pending. Kalau sudah SUCCESS, deposit
 * ditandai PAID dan saldo user dikreditkan. Idempoten via lockForUpdate.
 */
class SettlePendingDeposits extends Command
{
    protected $signature = 'deposits:settle-pending';

    protected $description = 'Cek status deposit pending ke BayarPro lalu kreditkan saldo (fallback webhook).';

    public function handle(BayarProService $bayarPro): int
    {
        $pending = Deposit::whereNotIn('status', ['PAID', 'FAILED'])
            ->whereNotNull('plat_order_num')
            ->where('created_at', '>=', now()->subDays(2))
            ->orderBy('id')
            ->limit(100)
            ->get();

        $settled = 0;

        foreach ($pending as $deposit) {
            try {
                $result = $bayarPro->checkStatus($deposit->plat_order_num);

                if (empty($result['success'])) {
                    continue;
                }

                $status = strtoupper((string) ($result['data']['status'] ?? ''));

                if ($status !== 'SUCCESS') {
                    continue;
                }

                DB::transaction(function () use ($deposit, $result, &$settled) {
                    $fresh = Deposit::where('id', $deposit->id)->lockForUpdate()->first();

                    if (!$fresh || $fresh->status === 'PAID') {
                        return;
                    }

                    $fresh->status = 'PAID';
                    $fresh->paid_at = now();
                    $fresh->gateway_response = $result['response'] ?: $fresh->gateway_response;
                    $fresh->save();

                    $user = User::lockForUpdate()->findOrFail($fresh->user_id);
                    $user->saldo = (float) $user->saldo + (float) $fresh->amount;
                    $user->save();

                    $settled++;

                    Log::info('Deposit settled via cron poller', [
                        'order_id' => $fresh->order_id,
                        'reference_id' => $fresh->plat_order_num,
                    ]);
                });
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
