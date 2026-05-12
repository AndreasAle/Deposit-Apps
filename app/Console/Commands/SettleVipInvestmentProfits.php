<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\UserInvestment;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SettleVipInvestmentProfits extends Command
{
    protected $signature = 'investments:settle-vip-profits';

    protected $description = 'Settle profit produk VIP yang sudah selesai durasinya ke saldo penarikan user';

    public function handle(): int
    {
        /*
        |--------------------------------------------------------------------------
        | Kategori VIP
        |--------------------------------------------------------------------------
        | category_id:
        | 2 = Saham Rubik
        | 3 = Rubik Pro
        */
        $vipCategoryIds = [2, 3];

        $processed = 0;

        UserInvestment::query()
            ->with('product:id,category_id,name')
            ->where('status', 'active')
            ->whereNotNull('end_date')
            ->where('end_date', '<=', now())
            ->whereHas('product', function ($q) use ($vipCategoryIds) {
                $q->whereIn('category_id', $vipCategoryIds);
            })
            ->orderBy('id')
            ->chunkById(100, function ($investments) use (&$processed) {
                foreach ($investments as $investment) {
                    DB::transaction(function () use ($investment, &$processed) {
                        /*
                        |--------------------------------------------------------------------------
                        | Lock ulang investment
                        |--------------------------------------------------------------------------
                        | Supaya aman dari double proses kalau cron kepanggil bersamaan.
                        */
                        $lockedInvestment = UserInvestment::where('id', $investment->id)
                            ->lockForUpdate()
                            ->first();

                        if (!$lockedInvestment || $lockedInvestment->status !== 'active') {
                            return;
                        }

                        if (!$lockedInvestment->end_date || $lockedInvestment->end_date > now()) {
                            return;
                        }

                        /*
                        |--------------------------------------------------------------------------
                        | Lock user
                        |--------------------------------------------------------------------------
                        */
                        $user = User::where('id', $lockedInvestment->user_id)
                            ->lockForUpdate()
                            ->first();

                        if (!$user) {
                            return;
                        }

                        $profit = (float) ($lockedInvestment->total_profit ?? 0);

                        if ($profit <= 0) {
                            $lockedInvestment->status = 'completed';
                            $lockedInvestment->save();

                            return;
                        }

                        /*
                        |--------------------------------------------------------------------------
                        | Masukkan profit ke saldo penarikan
                        |--------------------------------------------------------------------------
                        */
                        $user->saldo_penarikan = (float) ($user->saldo_penarikan ?? 0) + $profit;
                        $user->saldo_penarikan_total = (float) ($user->saldo_penarikan_total ?? 0) + $profit;
                        $user->save();

                        /*
                        |--------------------------------------------------------------------------
                        | Tandai investasi selesai
                        |--------------------------------------------------------------------------
                        */
                        $lockedInvestment->status = 'completed';
                        $lockedInvestment->save();

                        $processed++;
                    });
                }
            });

        $this->info("VIP investment profits settled: {$processed}");

        return self::SUCCESS;
    }
}