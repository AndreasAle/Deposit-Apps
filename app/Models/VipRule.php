<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VipRule extends Model
{
    protected $table = 'vip_rules';

    protected $fillable = [
        'vip_level',
        'min_total_deposit',
        'label',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
