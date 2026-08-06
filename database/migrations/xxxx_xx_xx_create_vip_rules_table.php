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
        Schema::hasTable('vip_rules') || Schema::create('vip_rules', function (Blueprint $table) {
            $table->id();

            $table->unsignedInteger('vip_level')->unique();
            $table->unsignedBigInteger('min_total_deposit');

            $table->string('label')->nullable();

            $table->boolean('is_active')->default(true);

            $table->timestamps();

            // Optional index untuk query cepat
            $table->index(['is_active', 'min_total_deposit']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vip_rules');
    }
};
