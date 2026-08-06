<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Dibuat idempoten: database produksi lahir dari import `deposit.sql`, bukan
 * dari `migrate`, sehingga tabelnya sudah ada sementara barisnya belum tercatat
 * di tabel `migrations`. Tanpa penjagaan ini `migrate` berhenti total di sini
 * dan SEMUA migration sesudahnya ikut tidak jalan.
 */
return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('forum_post_media')) {
            return;
        }

        Schema::create('forum_post_media', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_id')->constrained('forum_posts')->cascadeOnDelete();
            $table->string('type')->default('file'); // image|file
            $table->string('path');                 // storage path
            $table->string('original_name')->nullable();
            $table->string('mime')->nullable();
            $table->unsignedBigInteger('size')->nullable();
            $table->timestamps();

            $table->index(['post_id', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('forum_post_media');
    }
};
