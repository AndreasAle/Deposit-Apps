<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_investments', function (Blueprint $table) {
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
