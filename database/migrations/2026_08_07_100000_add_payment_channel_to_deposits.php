<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Menandai setiap deposit dengan saluran pembayaran yang dipakainya.
 *
 * Sebelumnya saluran ditentukan oleh satu setting global (config deposit.driver),
 * jadi tidak perlu disimpan per baris. Sekarang user bisa memilih sendiri antara
 * gateway BankPay dan QRIS statis, sehingga tiap deposit HARUS ingat lewat mana
 * dia dibuat — itu yang menentukan cara konfirmasinya (otomatis vs listener).
 *
 * Baris lama di-backfill: yang punya unique_code jelas milik QRIS statis,
 * sisanya deposit warisan BayarPro.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('deposits', 'payment_channel')) {
            Schema::table('deposits', function (Blueprint $table) {
                $table->string('payment_channel', 32)
                    ->nullable()
                    ->after('selected_channel');
            });
        }

        DB::table('deposits')
            ->whereNull('payment_channel')
            ->whereNotNull('unique_code')
            ->update(['payment_channel' => 'qris_statis']);

        DB::table('deposits')
            ->whereNull('payment_channel')
            ->update(['payment_channel' => 'bayarpro']);

        Schema::table('deposits', function (Blueprint $table) {
            $table->index(['payment_channel', 'status'], 'ix_deposits_channel_status');
        });
    }

    public function down(): void
    {
        Schema::table('deposits', function (Blueprint $table) {
            $table->dropIndex('ix_deposits_channel_status');
            $table->dropColumn('payment_channel');
        });
    }
};
