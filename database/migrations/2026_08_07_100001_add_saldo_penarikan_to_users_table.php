<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Menambahkan kolom `saldo_penarikan` yang selama ini dipakai kode tapi tidak
 * pernah punya migration - ia hanya ada di produksi karena ditambahkan manual
 * lewat SQL. Akibatnya database baru menghasilkan aplikasi yang seluruh alur
 * penarikannya langsung rusak.
 *
 * Idempoten: kolom yang sudah ada dilewati, jadi aman di database lama.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'saldo_penarikan')) {
                $table->decimal('saldo_penarikan', 15, 2)->default(0)->after('saldo');
            }
        });
    }

    public function down(): void
    {
        // Kolom sengaja tidak di-drop: isinya saldo user yang bisa ditarik.
    }
};
