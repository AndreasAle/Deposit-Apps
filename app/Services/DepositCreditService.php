<?php

namespace App\Services;

use App\Models\Deposit;
use App\Models\User;

/**
 * Satu-satunya tempat saldo user ditambah dari deposit.
 *
 * Dipakai bersama oleh notifikasi gateway BankPay, matcher notifikasi QRIS,
 * dan aksi manual admin - supaya aturan bisnisnya tidak pernah beda-beda
 * antar saluran maupun antar jalur konfirmasi.
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
     * Acuannya `real_amount` = nominal yang BENAR-BENAR dibayar user:
     *
     *   - Saluran QRIS statis: nominal diminta + kode unik. Default-nya saldo
     *     ditambah sebesar yang dibayar supaya user tidak pernah merasa
     *     kehilangan uang karena kode unik. Bisa dimatikan lewat config.
     *   - Saluran BankPay: `real_amount` diisi dari `payAmount` pada notifikasi
     *     gateway ("jumlah sebenarnya yang dibayarkan pelanggan"), sesuai
     *     instruksi dokumentasi untuk selalu memakai nominal riil dari API.
     */
    public function creditableAmount(Deposit $deposit): float
    {
        $requested = (float) $deposit->amount;

        if ($deposit->isQrisStatis() && !config('deposit.qris.credit_paid_amount', true)) {
            return $requested;
        }

        $paid = (float) ($deposit->real_amount ?: 0);

        return $paid > 0 ? $paid : $requested;
    }
}
