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
return new class extends Migration {
    public function up(): void
    {
        Schema::hasTable('forum_posts') || Schema::create('forum_posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->text('content')->nullable();
            $table->string('status')->default('published'); // published|hidden (opsional)
            $table->timestamps();

            $table->index(['user_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('forum_posts');
    }
};
