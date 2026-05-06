<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Withdrawal extends Model
{
    protected $fillable = [
        'user_id',
        'user_payout_account_id',

        // Gateway / JayaPay
        'order_id',
        'plat_order_num',
        'method',
        'bank_code',
        'account_no',
        'account_name',

        // Amount
        'amount',
        'fee',
        'net_amount',

        // Status
        'status',
        'gateway_status',
        'gateway_message',
        'gateway_response',
        'gateway_callback',

        // Admin / failure
        'admin_id',
        'reject_reason',
        'failed_reason',
        'proof_url',

        // Dates
        'requested_at',
        'processing_at',
        'approved_at',
        'paid_at',
        'failed_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'fee' => 'decimal:2',
        'net_amount' => 'decimal:2',

        'gateway_response' => 'array',
        'gateway_callback' => 'array',

        'requested_at' => 'datetime',
        'processing_at' => 'datetime',
        'approved_at' => 'datetime',
        'paid_at' => 'datetime',
        'failed_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Status Helpers
    |--------------------------------------------------------------------------
    */

    public function isPending(): bool
    {
        return $this->status === 'PENDING';
    }

    public function isProcessing(): bool
    {
        return $this->status === 'PROCESSING';
    }

    public function isPaid(): bool
    {
        return $this->status === 'PAID';
    }

    public function isFailed(): bool
    {
        return $this->status === 'FAILED';
    }

    public function isRejected(): bool
    {
        return $this->status === 'REJECTED';
    }

    public function isCancelled(): bool
    {
        return $this->status === 'CANCELLED';
    }

    public function canBeCancelled(): bool
    {
        return in_array($this->status, ['PENDING'], true);
    }

    public function canBeRejected(): bool
    {
        return in_array($this->status, ['PENDING', 'PROCESSING', 'APPROVED'], true);
    }

    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    */

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function payoutAccount()
    {
        return $this->belongsTo(UserPayoutAccount::class, 'user_payout_account_id');
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}