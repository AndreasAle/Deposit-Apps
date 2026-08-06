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
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // ======================
        // USERS TABLE
        // ======================
        Schema::hasTable('users') || Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone')->unique(); // login utama
            $table->string('password');

            // finance & role
            $table->unsignedBigInteger('saldo')->default(0);
            $table->unsignedTinyInteger('vip_level')->default(0);
            $table->string('role')->default('user');

            $table->rememberToken();
            $table->timestamps();
        });

        // ======================
        // SESSIONS (Laravel)
        // ======================
        Schema::hasTable('sessions') || Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('users');
    }
};
