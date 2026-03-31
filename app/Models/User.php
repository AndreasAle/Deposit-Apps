<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Kolom yang boleh diisi mass assignment
     */
    protected $fillable = [
        'name',
        'phone',
        'password',
        'saldo',
        'saldo_hold',
        'vip_level',
        'role',

        // referral fields
        'referral_code',
        'referred_by_user_id',
        'referral_earned_total',
    ];
    /**
     * Kolom yang disembunyikan saat serialize
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Casting attribute
     */
    protected $casts = [
        'password' => 'hashed',
    ];

    public function payoutAccounts()
    {
    return $this->hasMany(\App\Models\UserPayoutAccount::class);
    }

    public function withdrawals()
    {
        return $this->hasMany(\App\Models\Withdrawal::class);
        }
    public function referrer()
    {
        return $this->belongsTo(User::class, 'referred_by_user_id');
    }

    public function referrals()
    {
        return $this->hasMany(User::class, 'referred_by_user_id');
    }

    public function referralCommissions()
    {
        return $this->hasMany(\App\Models\ReferralCommission::class, 'referrer_id');
    }

}

    


