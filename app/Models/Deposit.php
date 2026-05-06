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
];

    protected $casts = [
        'amount' => 'decimal:2',
        'pay_fee' => 'decimal:2',
        'real_amount' => 'decimal:2',
        'expired_at' => 'datetime',
        'paid_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}