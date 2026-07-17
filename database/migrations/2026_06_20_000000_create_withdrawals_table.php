<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void
  {
    Schema::create('withdrawals', function (Blueprint $table) {
      $table->id();
      $table->unsignedBigInteger('user_id');
      $table->unsignedBigInteger('user_payout_account_id');

      $table->unsignedBigInteger('amount');     // nominal request
      $table->unsignedBigInteger('fee')->default(0);
      $table->unsignedBigInteger('net_amount'); // amount - fee

      $table->string('status', 20)->default('PENDING'); // PENDING|APPROVED|REJECTED|PAID|CANCELLED
      $table->unsignedBigInteger('admin_id')->nullable();

      $table->text('reject_reason')->nullable();
      $table->string('proof_url')->nullable();

      $table->timestamp('approved_at')->nullable();
      $table->timestamp('paid_at')->nullable();
      $table->timestamps();

      $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
      $table->foreign('user_payout_account_id')->references('id')->on('user_payout_accounts')->onDelete('restrict');

      $table->index(['status', 'created_at']);
      $table->index(['user_id', 'created_at']);
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('withdrawals');
  }
};
