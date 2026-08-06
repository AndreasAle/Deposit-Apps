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
        Schema::hasTable('products') || Schema::create('products', function (Blueprint $table) {
            $table->id();

            $table->foreignId('category_id')
                  ->constrained('product_categories')
                  ->cascadeOnDelete();

            $table->string('name');

            $table->unsignedBigInteger('price');          // Harga beli
            $table->unsignedBigInteger('daily_profit');   // Untung harian
            $table->integer('duration_days');             // Lama hari
            $table->unsignedBigInteger('total_profit');   // Total untung

            $table->integer('min_vip_level')->default(0); // Minimal VIP
            $table->boolean('is_active')->default(true);  // Status produk

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('products');
    }
};
