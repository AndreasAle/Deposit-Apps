<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\UserInvestment;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SettleInvestmentProfits extends Command
{
    protected $signature = 'investments:settle-profits';

    protected $description = 'Cairkan profit investasi yang sudah selesai durasinya ke saldo penarikan user';

    public function handle(): int
    {
        /*
        |--------------------------------------------------------------------------
        | Semua kategori, bukan hanya VIP
        |--------------------------------------------------------------------------
        | Dulu perintah ini sengaja hanya memproses kategori 2 dan 3, karena
        | produk BASIC (kategori 1) tidak punya profit sama sekali. Sekarang
        | BASIC juga berjalan selama durasinya, jadi profitnya harus ikut
        | dicairkan - kalau tidak, investasinya menggantung selamanya di
        | status active meski tanggalnya sudah lewat.
        */
        $processed = 0;

        UserInvestment::query()
            ->with('product:id,category_id,name')
            ->where('status', 'active')
            ->whereNotNull('end_date')
            ->where('end_date', '<=', now())
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
                            $lockedInvestment->status = 'finished';
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
                        $lockedInvestment->status = 'finished';
                        $lockedInvestment->save();

                        $processed++;
                    });
                }
            });

        $this->info("Investment profits settled: {$processed}");

        return self::SUCCESS;
    }
}