<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/*
 * Penjagaan `Schema::hasTable(...) ||` di bawah membuat migration ini idempoten.
 *
 * Database produksi lahir dari import `deposit.sql`, bukan dari `migrate`,
 * sehingga banyak tabel sudah ada sementara barisnya belum tercatat di tabel
 * `migrations`. Tanpa penjagaan ini `migrate` berhenti total di tabel pertama
 * yang sudah ada, dan SEMUA migration sesudahnya ikut tidak jalan - termasuk
 * yang menambah kolom baru.
 */
return new class extends Migration {
  public function up(): void
  {
    Schema::hasTable('user_payout_accounts') || Schema::create('user_payout_accounts', function (Blueprint $table) {
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
