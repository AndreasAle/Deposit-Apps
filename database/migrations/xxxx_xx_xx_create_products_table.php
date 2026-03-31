<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
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
