<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Kolom yang boleh diisi mass assignment.
     */
    protected $fillable = [
        'name',
        'phone',
        'password',

        // saldo
        'saldo',
        'saldo_penarikan',
        'saldo_penarikan_total',
        'saldo_hold',

        // level / role
        'vip_level',
        'role',

        // referral
        'referral_code',
        'referred_by_user_id',
        'referral_earned_total',
    ];

    /**
     * Kolom yang disembunyikan saat serialize.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Casting attribute.
     */
    protected $casts = [
        'password' => 'hashed',

        'saldo' => 'integer',
        'saldo_penarikan' => 'integer',
        'saldo_penarikan_total' => 'integer',
        'saldo_hold' => 'integer',

        'vip_level' => 'integer',
        'referred_by_user_id' => 'integer',
        'referral_earned_total' => 'decimal:2',
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

    public function investments()
    {
        return $this->hasMany(\App\Models\UserInvestment::class);
    }

    public function activeInvestments()
    {
        return $this->hasMany(\App\Models\UserInvestment::class)
            ->where('status', 'active');
    }
}