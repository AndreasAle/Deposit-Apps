<?php

namespace App\Services;

/**
 * Biaya admin penarikan.
 *
 * Sengaja satu tempat: sebelumnya penarikan sungguhan memotong 5% sementara
 * alat testing admin memakai angka mati Rp 7.800, sehingga hasil pengujian
 * tidak mewakili apa yang benar-benar diterima user.
 */
class WithdrawalFee
{
    public static function persen(): float
    {
        return (float) config('withdraw.fee_percent', 5);
    }

    /** Biaya dibulatkan ke rupiah penuh, mengikuti perilaku lama. */
    public static function hitung(int $amount): int
    {
        return (int) round($amount * self::persen() / 100);
    }

    /** Nominal bersih yang diterima user. */
    public static function bersih(int $amount): int
    {
        return max($amount - self::hitung($amount), 0);
    }

    /** "5%" untuk ditampilkan. */
    public static function label(): string
    {
        $persen = self::persen();

        return rtrim(rtrim(number_format($persen, 2, ',', '.'), '0'), ',') . '%';
    }
}
