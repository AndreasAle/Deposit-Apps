<?php

namespace App\Services;

use App\Models\Deposit;
use App\Models\User;

/**
 * Satu-satunya tempat saldo user ditambah dari deposit.
 *
 * Dipakai bersama oleh webhook BayarPro, matcher notifikasi QRIS, dan aksi
 * manual admin - supaya aturan bisnisnya tidak pernah beda-beda antar jalur.
 *
 * Aturan (sesuai kode lama):
 *   - Deposit hanya menambah saldo utama.
 *   - Deposit TIDAK menaikkan VIP.
 *   - Deposit TIDAK memberi komisi referral.
 *     Komisi referral hanya dari pembelian produk BASIC.
 *
 * Pemanggil WAJIB sudah berada di dalam DB transaction dan sudah mengunci
 * baris deposit (lockForUpdate), serta sudah memastikan statusnya belum PAID.
 */
class DepositCreditService
{
    public function credit(Deposit $deposit): float
    {
        $user = User::lockForUpdate()->findOrFail($deposit->user_id);

        $nominal = $this->creditableAmount($deposit);

        $user->saldo = (float) $user->saldo + $nominal;
        $user->save();

        return $nominal;
    }

    /**
     * Berapa yang ditambahkan ke saldo.
     *
     * Untuk driver qris_statis, user membayar nominal + kode unik. Default-nya
     * saldo ditambah sebesar yang BENAR-BENAR dibayar supaya user tidak pernah
     * merasa kehilangan uang karena kode unik.
     */
    public function creditableAmount(Deposit $deposit): float
    {
        $requested = (float) $deposit->amount;

        if (!config('deposit.qris.credit_paid_amount', true)) {
            return $requested;
        }

        $paid = (float) ($deposit->real_amount ?: 0);

        return $paid > 0 ? $paid : $requested;
    }
}
