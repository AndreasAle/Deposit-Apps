<?php

namespace App\Services;

use App\Models\ReferralCommission;
use App\Models\User;

class ReferralService
{
    public function give(User $referredUser, string $sourceType, int $sourceId, float $baseAmount, float $rate): void
    {
        if (!$referredUser->referred_by_user_id) {
            return;
        }

        $commission = round($baseAmount * $rate, 2);

        if ($commission <= 0) {
            return;
        }

        // Idempotent: kalau sudah pernah buat komisi untuk transaksi/source ini, stop.
        $created = ReferralCommission::firstOrCreate(
            [
                'source_type' => $sourceType,
                'source_id' => $sourceId,
            ],
            [
                'referrer_id' => $referredUser->referred_by_user_id,
                'referred_user_id' => $referredUser->id,
                'base_amount' => $baseAmount,
                'rate' => $rate,
                'commission_amount' => $commission,
            ]
        );

        // Kalau data lama sudah ada, jangan tambah saldo lagi.
        if (!$created->wasRecentlyCreated) {
            return;
        }

        $referrer = User::lockForUpdate()->find($referredUser->referred_by_user_id);

        if (!$referrer) {
            return;
        }

        /*
         * Referral masuk ke saldo penarikan, bukan saldo utama.
         */
        $referrer->saldo_penarikan = (float) ($referrer->saldo_penarikan ?? 0) + $commission;
        $referrer->saldo_penarikan_total = (float) ($referrer->saldo_penarikan_total ?? 0) + $commission;
        $referrer->referral_earned_total = (float) ($referrer->referral_earned_total ?? 0) + $commission;

        $referrer->save();
    }
}