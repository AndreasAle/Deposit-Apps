<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReferralCommission extends Model
{
    protected $fillable = [
        'referrer_id',
        'referred_user_id',
        'source_type',
        'source_id',
        'base_amount',
        'rate',
        'commission_amount',
    ];
}
