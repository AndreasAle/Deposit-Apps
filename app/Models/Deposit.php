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
        'payment_channel',
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

    /*
    |--------------------------------------------------------------------------
    | Kelayakan bertanya ke gateway
    |--------------------------------------------------------------------------
    | Gateway hanya boleh ditanyai soal invoice yang MASIH MUNGKIN dibayar.
    | Batasnya: belum berstatus final, dan belum lewat masa kedaluwarsa +
    | kelonggaran. Tanpa batas ini, invoice yang ditinggalkan user akan
    | ditanyakan berulang sampai jendela retensi habis - ribuan panggilan API
    | untuk sesuatu yang tidak akan pernah dibayar.
    |
    | Dipakai bersama oleh poller cron dan halaman invoice supaya keduanya
    | tidak mungkin punya batas yang berbeda.
    */

    /** Query scope untuk poller. */
    public function scopeMenungguGateway($query)
    {
        $grace = (int) config('services.bankpay.poll_grace_minutes', 30);

        return $query
            ->where('payment_channel', \App\Services\DepositChannels::BANKPAY)
            ->whereNotIn('status', ['PAID', 'FAILED'])
            ->where(function ($q) use ($grace) {
                $q->whereNull('expired_at')
                    ->orWhere('expired_at', '>', now()->subMinutes($grace));
            });
    }

    /** Versi satu baris untuk halaman invoice. */
    public function masihBisaDitanyakanKeGateway(): bool
    {
        if (!$this->isBankPay() || in_array($this->status, ['PAID', 'FAILED'], true)) {
            return false;
        }

        if (!$this->expired_at) {
            return true;
        }

        $grace = (int) config('services.bankpay.poll_grace_minutes', 30);

        return $this->expired_at->greaterThan(now()->subMinutes($grace));
    }

    /** Deposit ini dibuat lewat gateway BankPay (konfirmasi otomatis). */
    public function isBankPay(): bool
    {
        return $this->payment_channel === \App\Services\DepositChannels::BANKPAY;
    }

    /** Deposit ini dibuat lewat QRIS statis sendiri (konfirmasi listener). */
    public function isQrisStatis(): bool
    {
        return $this->payment_channel === \App\Services\DepositChannels::QRIS_STATIS;
    }

    public function isExpired(): bool
    {
        return $this->expired_at && now()->greaterThan($this->expired_at) && $this->status !== 'PAID';
    }
}