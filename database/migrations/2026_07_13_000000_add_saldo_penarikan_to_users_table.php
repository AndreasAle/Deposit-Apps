<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'saldo_penarikan')) {
                $table->unsignedBigInteger('saldo_penarikan')->default(0)->after('saldo');
            }

            if (!Schema::hasColumn('users', 'saldo_penarikan_total')) {
                $table->unsignedBigInteger('saldo_penarikan_total')->default(0)->after('saldo_penarikan');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'saldo_penarikan')) {
                $table->dropColumn('saldo_penarikan');
            }

            if (Schema::hasColumn('users', 'saldo_penarikan_total')) {
                $table->dropColumn('saldo_penarikan_total');
            }
        });
    }
};
