<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Menyelaraskan tabel deposits dengan kolom yang sebenarnya dipakai kode.
 *
 * Migration create_deposits_table cuma membuat 7 kolom, sementara
 * DepositController menulis 9 kolom lain yang tidak pernah ada migration-nya
 * (kemungkinan besar ditambahkan manual lewat SQL di server). Akibatnya
 * `migrate:fresh` di mesin baru menghasilkan aplikasi yang langsung rusak.
 *
 * Migration ini idempoten: kolom yang sudah ada dilewati, jadi aman dijalankan
 * di database lama maupun database baru.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('deposits', function (Blueprint $table) {
            if (!Schema::hasColumn('deposits', 'plat_order_num')) {
                $table->string('plat_order_num')->nullable()->after('order_id');
            }

            if (!Schema::hasColumn('deposits', 'selected_channel')) {
                $table->string('selected_channel', 32)->nullable()->after('method');
            }

            if (!Schema::hasColumn('deposits', 'pay_url')) {
                $table->text('pay_url')->nullable()->after('selected_channel');
            }

            if (!Schema::hasColumn('deposits', 'pay_data')) {
                $table->text('pay_data')->nullable()->after('pay_url');
            }

            if (!Schema::hasColumn('deposits', 'pay_fee')) {
                $table->decimal('pay_fee', 15, 2)->default(0)->after('pay_data');
            }

            if (!Schema::hasColumn('deposits', 'real_amount')) {
                $table->decimal('real_amount', 15, 2)->nullable()->after('pay_fee');
            }

            if (!Schema::hasColumn('deposits', 'expired_at')) {
                $table->timestamp('expired_at')->nullable()->after('real_amount');
            }

            if (!Schema::hasColumn('deposits', 'gateway_response')) {
                $table->json('gateway_response')->nullable()->after('expired_at');
            }

            if (!Schema::hasColumn('deposits', 'paid_at')) {
                $table->timestamp('paid_at')->nullable()->after('status');
            }

            // Kode unik yang ditambahkan ke nominal (driver qris_statis).
            if (!Schema::hasColumn('deposits', 'unique_code')) {
                $table->unsignedSmallInteger('unique_code')->nullable()->after('real_amount');
            }
        });

        // Enum lama cuma UNPAID/PAID/EXPIRED, padahal kode memakai FAILED di
        // tiga tempat. Di MySQL strict mode itu error. Longgarkan jadi varchar.
        // (SQLite menyimpan enum sebagai varchar + check constraint, tidak
        // perlu diubah — dan memang tidak mendukung MODIFY COLUMN.)
        if (in_array(DB::getDriverName(), ['mysql', 'mariadb'], true)) {
            DB::statement(
                "ALTER TABLE deposits MODIFY status VARCHAR(16) NOT NULL DEFAULT 'UNPAID'"
            );
        }

        Schema::table('deposits', function (Blueprint $table) {
            $table->index(['status', 'expired_at'], 'ix_deposits_status_expired');
        });
    }

    public function down(): void
    {
        Schema::table('deposits', function (Blueprint $table) {
            $table->dropIndex('ix_deposits_status_expired');
        });

        // Kolom sengaja tidak di-drop: data pembayaran historis ada di sana.
        if (in_array(DB::getDriverName(), ['mysql', 'mariadb'], true)) {
            DB::statement(
                "ALTER TABLE deposits MODIFY status ENUM('UNPAID','PAID','EXPIRED') NOT NULL DEFAULT 'UNPAID'"
            );
        }
    }
};
