<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mutation extends Model
{
    protected $fillable = [
        'ext_id',
        'fingerprint',
        'amount',
        'raw',
        'source',
        'device',
        'status',
        'deposit_id',
        'candidate_deposit_id',
        'note',
        'notified_at',
    ];

    protected $casts = [
        'amount' => 'integer',
        'notified_at' => 'datetime',
    ];

    public function deposit()
    {
        return $this->belongsTo(Deposit::class);
    }

    public function isMatched(): bool
    {
        return $this->status === 'matched';
    }

    public function needsAttention(): bool
    {
        return in_array($this->status, ['unmatched', 'needs_review'], true);
    }
}
