<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Menyelaraskan cakupan ux_deposits_pending_unique_amount.
 *
 * Versi pertama migration 100002 mencakup SEMUA deposit UNPAID. Itu terlalu
 * luas: deposit warisan BayarPro wajar punya nominal kembar (banyak orang
 * deposit 50.000 bersamaan) dan identitasnya bukan dari nominal, sehingga
 * index-nya gagal dipasang di database produksi yang masih punya invoice
 * BayarPro aktif.
 *
 * Migration ini mempersempit cakupannya ke deposit yang punya unique_code,
 * yaitu deposit driver qris_statis. Aman dijalankan di database yang sudah
 * memakai definisi baru (hasilnya sama).
 */
return new class extends Migration
{
    public function up(): void
    {
        $driver = DB::getDriverName();

        if ($driver === 'mysql' || $driver === 'mariadb') {
            if ($this->indexExists()) {
                DB::statement("ALTER TABLE deposits DROP INDEX ux_deposits_pending_unique_amount");
            }

            if (Schema::hasColumn('deposits', 'pending_unique_amount')) {
                DB::statement("ALTER TABLE deposits DROP COLUMN pending_unique_amount");
            }

            DB::statement("
                ALTER TABLE deposits
                ADD COLUMN pending_unique_amount BIGINT UNSIGNED
                    GENERATED ALWAYS AS (
                        CASE WHEN status = 'UNPAID' AND unique_code IS NOT NULL
                             THEN CAST(real_amount AS UNSIGNED)
                             ELSE NULL
                        END
                    ) STORED
            ");

            DB::statement("
                ALTER TABLE deposits
                ADD UNIQUE INDEX ux_deposits_pending_unique_amount (pending_unique_amount)
            ");

            return;
        }

        DB::statement("DROP INDEX IF EXISTS ux_deposits_pending_unique_amount");

        DB::statement("
            CREATE UNIQUE INDEX ux_deposits_pending_unique_amount
            ON deposits (real_amount)
            WHERE status = 'UNPAID' AND unique_code IS NOT NULL
        ");
    }

    public function down(): void
    {
        // Tidak dibalik: definisi lama justru yang bermasalah.
    }

    private function indexExists(): bool
    {
        return collect(DB::select("SHOW INDEX FROM deposits"))
            ->contains(fn ($row) => $row->Key_name === 'ux_deposits_pending_unique_amount');
    }
};
