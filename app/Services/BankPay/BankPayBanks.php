<?php

namespace App\Services\BankPay;

/**
 * Peta tujuan penarikan: provider di sistem kita -> kode bank BankPay.
 *
 * Kode diambil dari lampiran "Daftar Bank Indonesia" pada dokumentasi BankPay.
 * Daftar lengkapnya 138 bank; yang dicantumkan di sini adalah bank dan
 * e-wallet yang benar-benar bisa dipilih user sebagai rekening penarikan.
 *
 * Kalau perlu menambah tujuan baru, cukup tambahkan barisnya di sini —
 * validasi withdraw otomatis ikut mengenali provider tersebut.
 */
class BankPayBanks
{
    /** @var array<string, array{code:string, name:string}> */
    public const TARGETS = [
        // E-wallet
        'OVO' => ['code' => '1', 'name' => 'OVO'],
        'DANA' => ['code' => '2', 'name' => 'DANA'],
        'GOPAY' => ['code' => '3', 'name' => 'GOPAY'],
        'SHOPEEPAY' => ['code' => '4', 'name' => 'SHOPEEPAY'],
        'LINKAJA' => ['code' => '5', 'name' => 'LINKAJA'],

        // Bank
        'BCA' => ['code' => '126', 'name' => 'Bank BCA'],
        'BRI' => ['code' => '132', 'name' => 'Bank BRI'],
        'BNI' => ['code' => '129', 'name' => 'Bank BNI'],
        'MANDIRI' => ['code' => '130', 'name' => 'Bank Mandiri'],
        'PERMATA' => ['code' => '127', 'name' => 'Permata Bank'],
        'CIMB' => ['code' => '122', 'name' => 'Bank CIMB Niaga'],
        'DANAMON' => ['code' => '128', 'name' => 'Bank Danamon'],
        'BTN' => ['code' => '50', 'name' => 'Bank Tabungan Negara'],
        'BSI' => ['code' => '136', 'name' => 'Bank BSI'],
        'MEGA' => ['code' => '45', 'name' => 'Bank Mega'],
        'SINARMAS' => ['code' => '58', 'name' => 'Bank Sinarmas'],
        'OCBC' => ['code' => '120', 'name' => 'Bank OCBC NISP'],
        'PANIN' => ['code' => '124', 'name' => 'Bank Panin'],
        'MAYBANK' => ['code' => '125', 'name' => 'Bank BII Maybank'],
        'BUKOPIN' => ['code' => '43', 'name' => 'Bank Bukopin'],
        'MUAMALAT' => ['code' => '61', 'name' => 'Bank Muamalat'],
        'SEABANK' => ['code' => '135', 'name' => 'SEABANK INDONESIA'],
        'NEO' => ['code' => '137', 'name' => 'BANK NEO COMMERCE'],
        'BNC' => ['code' => '138', 'name' => 'Bank BNC'],
        'JENIUS' => ['code' => '48', 'name' => 'JENIUS'],
        'BJB' => ['code' => '89', 'name' => 'Bank Jabar dan Banten'],
        'DKI' => ['code' => '88', 'name' => 'Bank DKI'],
        'JATIM' => ['code' => '85', 'name' => 'Bank Jatim'],
        'JATENG' => ['code' => '86', 'name' => 'Bank Jateng'],
    ];

    /**
     * Normalisasi nama provider dari rekening user.
     */
    public static function normalize(?string $provider): string
    {
        return strtoupper(trim((string) $provider));
    }

    public static function supports(?string $provider): bool
    {
        return isset(self::TARGETS[self::normalize($provider)]);
    }

    /**
     * @return array{code:string, name:string}|null
     */
    public static function find(?string $provider): ?array
    {
        return self::TARGETS[self::normalize($provider)] ?? null;
    }
}
