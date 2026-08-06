<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mutations', function (Blueprint $table) {
            $table->id();

            // ID notifikasi dari HP listener. Dipakai untuk menolak kiriman
            // ulang (MacroDroid retry / notif dobel).
            $table->string('ext_id', 191)->unique();

            // Hash dari nominal + teks notif. Kalau ext_id kebetulan kembar
            // tapi fingerprint beda, berarti ini pembayaran LAIN, bukan retry.
            $table->string('fingerprint', 32)->nullable()->index();

            $table->unsignedBigInteger('amount')->nullable()->index();
            $table->text('raw')->nullable();
            $table->string('source', 64)->nullable();
            $table->string('device', 64)->nullable();

            // matched | unmatched | needs_review
            $table->string('status', 20)->index();

            $table->foreignId('deposit_id')->nullable()
                  ->constrained('deposits')->nullOnDelete();
            $table->unsignedBigInteger('candidate_deposit_id')->nullable();

            $table->string('note', 255)->nullable();
            $table->timestamp('notified_at')->nullable();

            $table->timestamps();
        });

        Schema::create('listener_heartbeats', function (Blueprint $table) {
            $table->string('device', 64)->primary();
            $table->timestamp('last_seen');
            $table->string('info', 255)->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('listener_heartbeats');
        Schema::dropIfExists('mutations');
    }
};
