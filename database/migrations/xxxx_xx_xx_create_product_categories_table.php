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
    public function up()
    {
        Schema::hasTable('product_categories') || Schema::create('product_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');           // Reguler, Harian, Premium
            $table->string('slug')->unique(); // reguler, harian, premium
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('product_categories');
    }
};
