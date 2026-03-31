<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void
  {
    Schema::create('user_payout_accounts', function (Blueprint $table) {
      $table->id();
      $table->unsignedBigInteger('user_id');

      // BANK atau EWALLET
      $table->string('type', 20); // 'BANK' | 'EWALLET'
      $table->string('provider', 50); // contoh: BCA, BRI, DANA, OVO, GOPAY
      $table->string('account_name', 100);
      $table->string('account_number', 50); // no rek / no hp ewallet

      $table->boolean('is_default')->default(false);
      $table->timestamps();

      $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
      $table->index(['user_id', 'type']);
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('user_payout_accounts');
  }
};
