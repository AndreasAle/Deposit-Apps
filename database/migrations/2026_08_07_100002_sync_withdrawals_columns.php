<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Menyelaraskan tabel `withdrawals` dengan kolom yang sebenarnya dipakai kode.
 *
 * Migration create_withdrawals_table hanya membuat 14 kolom, sementara
 * WithdrawalController dan admin menulis 15 kolom lain yang tidak pernah punya
 * migration - ditambahkan manual lewat SQL di server. Akibatnya `migrate:fresh`
 * di mesin baru menghasilkan aplikasi yang seluruh alur penarikannya rusak.
 *
 * Sama persis dengan yang dulu terjadi pada tabel `deposits`.
 *
 * Idempoten: kolom yang sudah ada dilewati, jadi aman di database lama.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('withdrawals', function (Blueprint $table) {
            // Identitas pesanan di gateway.
            if (!Schema::hasColumn('withdrawals', 'order_id')) {
                $table->string('order_id', 64)->nullable()->after('user_payout_account_id');
            }

            if (!Schema::hasColumn('withdrawals', 'plat_order_num')) {
                $table->string('plat_order_num')->nullable()->after('order_id');
            }

            // Salinan rekening tujuan saat pengajuan. Sengaja disalin, bukan
            // hanya direlasikan: kalau user mengubah rekeningnya nanti,
            // riwayat penarikan harus tetap menunjukkan ke mana dana dikirim.
            if (!Schema::hasColumn('withdrawals', 'method')) {
                $table->string('method', 32)->nullable()->after('plat_order_num');
            }

            if (!Schema::hasColumn('withdrawals', 'bank_code')) {
                $table->string('bank_code', 32)->nullable()->after('method');
            }

            if (!Schema::hasColumn('withdrawals', 'account_no')) {
                $table->string('account_no', 64)->nullable()->after('bank_code');
            }

            if (!Schema::hasColumn('withdrawals', 'account_name')) {
                $table->string('account_name')->nullable()->after('account_no');
            }

            // Jejak percakapan dengan gateway, untuk audit saat ada sengketa.
            if (!Schema::hasColumn('withdrawals', 'gateway_status')) {
                $table->string('gateway_status', 32)->nullable()->after('status');
            }

            if (!Schema::hasColumn('withdrawals', 'gateway_message')) {
                $table->text('gateway_message')->nullable()->after('gateway_status');
            }

            if (!Schema::hasColumn('withdrawals', 'gateway_response')) {
                $table->json('gateway_response')->nullable()->after('gateway_message');
            }

            if (!Schema::hasColumn('withdrawals', 'gateway_callback')) {
                $table->json('gateway_callback')->nullable()->after('gateway_response');
            }

            if (!Schema::hasColumn('withdrawals', 'failed_reason')) {
                $table->text('failed_reason')->nullable()->after('reject_reason');
            }

            if (!Schema::hasColumn('withdrawals', 'requested_at')) {
                $table->timestamp('requested_at')->nullable()->after('approved_at');
            }

            if (!Schema::hasColumn('withdrawals', 'processing_at')) {
                $table->timestamp('processing_at')->nullable()->after('requested_at');
            }

            if (!Schema::hasColumn('withdrawals', 'processed_at')) {
                $table->timestamp('processed_at')->nullable()->after('processing_at');
            }

            if (!Schema::hasColumn('withdrawals', 'failed_at')) {
                $table->timestamp('failed_at')->nullable()->after('paid_at');
            }
        });

        // Enum/varchar status asli hanya memuat sebagian status yang dipakai
        // kode (PROCESSING dan FAILED tidak ada di daftar aslinya).
        if (in_array(DB::getDriverName(), ['mysql', 'mariadb'], true)) {
            DB::statement(
                "ALTER TABLE withdrawals MODIFY status VARCHAR(20) NOT NULL DEFAULT 'PENDING'"
            );
        }

        // Pencocokan notifikasi gateway memakai order_id kita sendiri, jadi
        // kolom ini wajib dicari cepat dan tidak boleh kembar.
        if (Schema::hasColumn('withdrawals', 'order_id') && !$this->indexAda('ux_withdrawals_order_id')) {
            Schema::table('withdrawals', function (Blueprint $table) {
                $table->unique('order_id', 'ux_withdrawals_order_id');
            });
        }
    }

    public function down(): void
    {
        // Kolom sengaja tidak di-drop: berisi jejak pencairan dana sungguhan.
    }

    private function indexAda(string $nama): bool
    {
        try {
            if (in_array(DB::getDriverName(), ['mysql', 'mariadb'], true)) {
                return collect(DB::select('SHOW INDEX FROM withdrawals'))
                    ->contains(fn ($row) => $row->Key_name === $nama);
            }

            return collect(DB::select("PRAGMA index_list('withdrawals')"))
                ->contains(fn ($row) => ($row->name ?? '') === $nama);
        } catch (\Throwable) {
            return false;
        }
    }
};
