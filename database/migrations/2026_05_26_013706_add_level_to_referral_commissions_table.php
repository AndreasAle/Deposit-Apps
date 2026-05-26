<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('referral_commissions', function (Blueprint $table) {
            // Tambah kolom level (1, 2, atau 3)
            $table->unsignedTinyInteger('level')->default(1)->after('source_id');

            // Hapus unique lama (hanya source_type + source_id)
            // karena sekarang 1 transaksi bisa punya 3 komisi (L1, L2, L3)
            $table->dropUnique(['source_type', 'source_id']);

            // Unique baru: 1 record per level per transaksi
            $table->unique(['source_type', 'source_id', 'level']);
        });
    }

    public function down(): void
    {
        Schema::table('referral_commissions', function (Blueprint $table) {
            $table->dropUnique(['source_type', 'source_id', 'level']);
            $table->unique(['source_type', 'source_id']);
            $table->dropColumn('level');
        });
    }
};
