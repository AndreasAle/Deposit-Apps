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
        'start_date' => 'datetime',
        'end_date'   => 'datetime',
        'price'      => 'integer',
        'daily_profit' => 'integer',
        'duration_days' => 'integer',
        'total_profit' => 'integer',
    ];

    /**
     * Relasi ke Product
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
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
     * Helper: cek aktif
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }
}
