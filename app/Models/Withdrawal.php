<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Withdrawal extends Model
{
  protected $fillable = [
    'user_id','user_payout_account_id','amount','fee','net_amount',
    'status','admin_id','reject_reason','proof_url','approved_at','paid_at'
  ];

  protected $casts = [
    'approved_at' => 'datetime',
    'paid_at' => 'datetime',
  ];

  public function user()
  {
    return $this->belongsTo(User::class);
  }

  public function payoutAccount()
  {
    return $this->belongsTo(UserPayoutAccount::class, 'user_payout_account_id');
  }
}
