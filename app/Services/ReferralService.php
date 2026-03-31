<?php

namespace App\Services;

use App\Models\ReferralCommission;
use App\Models\User;

class ReferralService
{
    public function give(User $referredUser, string $sourceType, int $sourceId, float $baseAmount, float $rate): void
    {
        if (!$referredUser->referred_by_user_id) return;

        $commission = round($baseAmount * $rate, 2);
        if ($commission <= 0) return;

        // idempotent: kalau sudah pernah buat untuk source ini, stop
        $created = ReferralCommission::firstOrCreate(
            ['source_type' => $sourceType, 'source_id' => $sourceId],
            [
                'referrer_id' => $referredUser->referred_by_user_id,
                'referred_user_id' => $referredUser->id,
                'base_amount' => $baseAmount,
                'rate' => $rate,
                'commission_amount' => $commission,
            ]
        );

        // Kalau firstOrCreate "ketemu" record lama, jangan nambah saldo lagi
        if (!$created->wasRecentlyCreated) return;

        // lock saldo referrer biar aman dari race (panggil ini saat sudah dalam DB transaction)
        $referrer = User::lockForUpdate()->find($referredUser->referred_by_user_id);
        if (!$referrer) return;

        $referrer->saldo += $commission;
        $referrer->referral_earned_total += $commission;
        $referrer->save();
    }
}