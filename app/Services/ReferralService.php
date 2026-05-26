<?php

namespace App\Services;

use App\Models\ReferralCommission;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ReferralService
{
    // Rate komisi per level
    private const RATES = [
        1 => 0.32,  // 32% — referrer langsung
        2 => 0.02,  // 2%  — referrer dari referrer
        3 => 0.01,  // 1%  — level 3
    ];

    /**
     * Distribusikan komisi 3 level ke atas chain referral.
     *
     * Contoh:
     *   Andi → Isya → Rumi beli produk
     *   Level 1: Isya  dapat 32% dari harga produk Rumi
     *   Level 2: Andi  dapat  2% dari harga produk Rumi
     *   Level 3: (tidak ada referrer Andi → berhenti)
     */
    public function give(User $buyer, string $sourceType, int $sourceId, float $baseAmount): void
    {
        $current = $buyer;

        for ($level = 1; $level <= 3; $level++) {
            // Tidak ada referrer di level ini, hentikan
            if (!$current->referred_by_user_id) {
                break;
            }

            $rate       = self::RATES[$level];
            $commission = round($baseAmount * $rate, 2);

            if ($commission <= 0) {
                // Lanjut naik ke level berikutnya
                $current = User::find($current->referred_by_user_id);
                if (!$current) break;
                continue;
            }

            // Idempotent: jangan dobel bayar untuk source + level yang sama
            $record = ReferralCommission::firstOrCreate(
                [
                    'source_type' => $sourceType,
                    'source_id'   => $sourceId,
                    'level'       => $level,
                ],
                [
                    'referrer_id'       => $current->referred_by_user_id,
                    'referred_user_id'  => $buyer->id,
                    'base_amount'       => $baseAmount,
                    'rate'              => $rate,
                    'commission_amount' => $commission,
                ]
            );

            if ($record->wasRecentlyCreated) {
                // Lock referrer agar aman dari race condition
                $referrer = User::lockForUpdate()->find($current->referred_by_user_id);

                if ($referrer) {
                    $referrer->saldo_penarikan       = (float) ($referrer->saldo_penarikan       ?? 0) + $commission;
                    $referrer->saldo_penarikan_total = (float) ($referrer->saldo_penarikan_total ?? 0) + $commission;
                    $referrer->referral_earned_total = (float) ($referrer->referral_earned_total ?? 0) + $commission;
                    $referrer->save();
                }
            }

            // Naik satu level ke referrer berikutnya
            $current = User::find($current->referred_by_user_id);
            if (!$current) break;
        }
    }
}
