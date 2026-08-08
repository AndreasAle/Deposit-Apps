<?php

namespace App\Services;

use Carbon\CarbonImmutable;

/**
 * Jam layanan penarikan.
 *
 * Satu tempat untuk menjawab "apakah penarikan sedang dibuka?", dipakai oleh
 * tampilan (mematikan tombol) dan controller (menolak pengajuan). Keduanya
 * WAJIB memakai jawaban yang sama - tombol yang mati hanyalah kesopanan;
 * yang benar-benar menjaga adalah penolakan di server, karena tombol bisa
 * dihidupkan siapa saja lewat inspect element.
 *
 * Perhitungannya selalu dalam zona waktu yang dikonfigurasi (WIB), bukan zona
 * server yang berjalan pada UTC.
 */
class WithdrawWindow
{
    public static function enabled(): bool
    {
        return (bool) config('withdraw.hours.enabled', false);
    }

    public static function timezone(): string
    {
        return (string) config('withdraw.hours.timezone', 'Asia/Jakarta');
    }

    public static function isOpen(?CarbonImmutable $saat = null): bool
    {
        if (!self::enabled()) {
            return true;
        }

        $saat = ($saat ?? CarbonImmutable::now())->setTimezone(self::timezone());

        $menit = $saat->hour * 60 + $saat->minute;
        $mulai = self::keMenit(config('withdraw.hours.start', '09:00'));
        $selesai = self::keMenit(config('withdraw.hours.end', '21:00'));

        // Jam yang melewati tengah malam (mis. 21:00 - 03:00) terbaca terbalik,
        // jadi logikanya dibalik juga: buka kalau di LUAR rentang tertutupnya.
        if ($mulai > $selesai) {
            return $menit >= $mulai || $menit < $selesai;
        }

        return $menit >= $mulai && $menit < $selesai;
    }

    /** "09:00 - 21:00 WIB" */
    public static function label(): string
    {
        return trim(sprintf(
            '%s - %s %s',
            self::rapikan(config('withdraw.hours.start', '09:00')),
            self::rapikan(config('withdraw.hours.end', '21:00')),
            (string) config('withdraw.hours.label', '')
        ));
    }

    /** Kalimat yang ditunjukkan ke user saat penarikan sedang tutup. */
    public static function pesanTutup(): string
    {
        return 'Penarikan hanya dapat diajukan pada jam ' . self::label()
            . '. Silakan coba lagi pada jam tersebut.';
    }

    private static function keMenit(string $jam): int
    {
        [$j, $m] = array_pad(explode(':', trim($jam)), 2, '0');

        return ((int) $j) * 60 + (int) $m;
    }

    private static function rapikan(string $jam): string
    {
        [$j, $m] = array_pad(explode(':', trim($jam)), 2, '0');

        return str_pad((string) (int) $j, 2, '0', STR_PAD_LEFT) . ':'
            . str_pad((string) (int) $m, 2, '0', STR_PAD_LEFT);
    }
}
