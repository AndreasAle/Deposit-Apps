<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('deposits', function (Blueprint $table) {
            $table->id();

            // RELASI USER
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->cascadeOnDelete();

            // IDENTITAS TRANSAKSI
            $table->string('order_id')->unique();

            // NOMINAL DEPOSIT
            $table->unsignedBigInteger('amount');

            // METODE PEMBAYARAN (MANUAL / TRIPAY / DLL)
            $table->string('method')->default('MANUAL');

            // STATUS TRANSAKSI
            $table->enum('status', ['UNPAID', 'PAID', 'EXPIRED'])
                  ->default('UNPAID');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deposits');
    }
};
