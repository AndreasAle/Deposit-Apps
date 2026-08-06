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
        Schema::hasTable('mutations') || Schema::create('mutations', function (Blueprint $table) {
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

        Schema::hasTable('listener_heartbeats') || Schema::create('listener_heartbeats', function (Blueprint $table) {
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
