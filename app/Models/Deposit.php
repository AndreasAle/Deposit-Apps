<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Deposit extends Model
{
    protected $fillable = [
        'user_id',
        'order_id',
        'plat_order_num',
        'amount',
        'method',
        'selected_channel',
        'pay_url',
        'pay_data',
        'pay_fee',
        'real_amount',
        'expired_at',
        'gateway_response',
        'status',
        'paid_at',
        'unique_code',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'pay_fee' => 'decimal:2',
        'real_amount' => 'decimal:2',
        'expired_at' => 'datetime',
        'paid_at' => 'datetime',
        'gateway_response' => 'array',
        'unique_code' => 'integer',
    ];

    /*
    | Catatan: kolom `pending_unique_amount` di tabel deposits adalah GENERATED
    | COLUMN milik MySQL (berisi nominal bayar selama status UNPAID, NULL
    | setelahnya). Jangan pernah dimasukkan ke $fillable atau di-set manual -
    | MySQL akan menolak penulisannya.
    */

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isPaid(): bool
    {
        return $this->status === 'PAID';
    }

    public function isUnpaid(): bool
    {
        return $this->status === 'UNPAID';
    }

    public function isFailed(): bool
    {
        return $this->status === 'FAILED';
    }

    public function isExpired(): bool
    {
        return $this->expired_at && now()->greaterThan($this->expired_at) && $this->status !== 'PAID';
    }
}