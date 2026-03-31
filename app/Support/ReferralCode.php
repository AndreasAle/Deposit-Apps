<?php

namespace App\Support;

use App\Models\User;
use Illuminate\Support\Str;

class ReferralCode
{
    public static function generateUnique(int $len = 10): string
    {
        do {
            $code = strtoupper(Str::random($len));
        } while (User::where('referral_code', $code)->exists());

        return $code;
    }
}
