<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Kunci anti-tertukar untuk deposit QRIS statis.
 *
 * MySQL tidak punya partial unique index seperti SQLite/Postgres, jadi dipakai
 * generated column: nominal hanya "terlihat" oleh index selama status UNPAID.
 * Begitu deposit jadi PAID/EXPIRED/FAILED nilainya jadi NULL, dan MySQL tidak
 * menganggap NULL sebagai duplikat -> nominal uniknya otomatis bebas dipakai lagi.
 *
 * Efeknya: DUA DEPOSIT UNPAID TIDAK MUNGKIN punya nominal bayar yang sama.
 * Bukan sekadar "diusahakan beda" - dijamin oleh database. Kalau ada race
 * condition, INSERT-nya yang gagal, bukan uangnya yang tertukar.
 */
return new class extends Migration
{
    public function up(): void
    {
        $driver = DB::getDriverName();

        if ($driver === 'mysql' || $driver === 'mariadb') {
            DB::statement("
                ALTER TABLE deposits
                ADD COLUMN pending_unique_amount BIGINT UNSIGNED
                    GENERATED ALWAYS AS (
                        CASE WHEN status = 'UNPAID'
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

        // SQLite & Postgres punya partial unique index, jadi tidak butuh
        // generated column. Efeknya sama persis. (SQLite dipakai test suite.)
        DB::statement("
            CREATE UNIQUE INDEX ux_deposits_pending_unique_amount
            ON deposits (real_amount)
            WHERE status = 'UNPAID'
        ");
    }

    public function down(): void
    {
        $driver = DB::getDriverName();

        if ($driver === 'mysql' || $driver === 'mariadb') {
            DB::statement("ALTER TABLE deposits DROP INDEX ux_deposits_pending_unique_amount");
            DB::statement("ALTER TABLE deposits DROP COLUMN pending_unique_amount");

            return;
        }

        DB::statement("DROP INDEX IF EXISTS ux_deposits_pending_unique_amount");
    }
};
