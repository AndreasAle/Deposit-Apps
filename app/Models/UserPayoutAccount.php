<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserPayoutAccount extends Model
{
  protected $fillable = [
    'user_id','type','provider','account_name','account_number','is_default'
  ];

  public function user()
  {
    return $this->belongsTo(User::class);
  }
}
