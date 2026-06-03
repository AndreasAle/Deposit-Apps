<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserInvestment extends Model
{
    use HasFactory;

    /**
     * Jika nama tabel bukan "user_investments", aktifkan ini.
     * protected $table = 'user_investments';
     */

    protected $fillable = [
        'user_id',
        'product_id',
        'price',
        'daily_profit',
        'duration_days',
        'total_profit',
        'start_date',
        'end_date',
        'status',
    ];

    /**
     * Biar start_date & end_date otomatis jadi Carbon object.
     */
    protected $casts = [
        'start_date'    => 'datetime',
        'end_date'      => 'datetime',
        'price'         => 'integer',
        'daily_profit'  => 'integer',
        'duration_days' => 'integer',
        'total_profit'  => 'integer',
    ];

    /**
     * Relasi ke Product
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    /**
     * Relasi ke User
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Scope: investasi aktif
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope: milik user tertentu
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope: hanya produk yang boleh masuk profit harian.
     *
     * Rule client:
     * category_id = 1 / Semua       => tidak masuk profit
     * category_id = 2 / Saham Velora => masuk profit
     * category_id = 3 / Velora Pro   => masuk profit
     */
    public function scopeProfitEligible($query)
    {
        return $query->whereHas('product', function ($q) {
            $q->whereIn('category_id', [2, 3]);
        });
    }

    /**
     * Scope: produk yang tidak masuk profit.
     */
    public function scopeNonProfit($query)
    {
        return $query->whereHas('product', function ($q) {
            $q->where('category_id', 1);
        });
    }

    /**
     * Helper: cek aktif
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Helper: cek apakah investasi ini boleh masuk profit.
     */
    public function isProfitEligible(): bool
    {
        return in_array((int) data_get($this->product, 'category_id'), [2, 3], true);
    }
}