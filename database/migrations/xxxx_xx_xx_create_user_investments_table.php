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
return new class extends Migration
{
    public function up(): void
    {
        Schema::hasTable('user_investments') || Schema::create('user_investments', function (Blueprint $table) {
            $table->id();

            // RELASI
            $table->foreignId('user_id')
                  ->constrained()
                  ->cascadeOnDelete();

            $table->foreignId('product_id')
                  ->constrained()
                  ->cascadeOnDelete();

            // DATA INVESTASI
            $table->unsignedBigInteger('price');         // harga beli
            $table->unsignedBigInteger('daily_profit');  // untung harian
            $table->integer('duration_days');            // durasi (hari)
            $table->unsignedBigInteger('total_profit');  // total untung

            // WAKTU
            $table->date('start_date');
            $table->date('end_date');

            // STATUS
            $table->enum('status', ['active', 'finished'])
                  ->default('active');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_investments');
    }
};
