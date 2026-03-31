<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vip_rules', function (Blueprint $table) {
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
