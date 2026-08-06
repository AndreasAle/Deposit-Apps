<?php

namespace App\Services;

/**
 * Satu tempat untuk menjawab "saluran deposit apa saja yang aktif?".
 *
 * Dipakai bersama oleh halaman deposit (menampilkan pilihan), controller
 * (memvalidasi pilihan user), dan admin (menampilkan asal sebuah deposit),
 * supaya daftar saluran tidak pernah berbeda antar tempat.
 */
class DepositChannels
{
    public const BANKPAY = 'bankpay';
    public const QRIS_STATIS = 'qris_statis';

    /**
     * Saluran yang aktif, terurut sesuai config.
     *
     * @return array<string, array{code:string, name:string, desc:string, auto_confirm:bool, degraded:bool}>
     */
    public static function enabled(): array
    {
        $out = [];

        foreach ((array) config('deposit.channels', []) as $code => $channel) {
            if (empty($channel['enabled'])) {
                continue;
            }

            $out[$code] = [
                'code' => $code,
                'name' => (string) ($channel['name'] ?? $code),
                'desc' => (string) ($channel['desc'] ?? ''),
                'auto_confirm' => (bool) ($channel['auto_confirm'] ?? false),
                'degraded' => (bool) ($channel['degraded'] ?? false),
            ];
        }

        return $out;
    }

    /** Saluran aktif yang sedang tidak ditandai bermasalah. */
    public static function healthy(): array
    {
        return array_filter(self::enabled(), fn (array $c) => !$c['degraded']);
    }

    public static function isDegraded(?string $code): bool
    {
        return (bool) (self::enabled()[$code]['degraded'] ?? false);
    }

    /**
     * Pengumuman untuk halaman deposit.
     *
     * Teks manual dari .env diutamakan. Kalau kosong tapi ada saluran yang
     * ditandai bermasalah, kalimatnya disusun sendiri supaya saat gangguan
     * cukup menyalakan satu tanda tanpa perlu mengarang kalimat.
     */
    public static function notice(): ?string
    {
        $manual = trim((string) config('deposit.notice', ''));

        if ($manual !== '') {
            return $manual;
        }

        $bermasalah = array_filter(self::enabled(), fn (array $c) => $c['degraded']);

        if ($bermasalah === []) {
            return null;
        }

        $sehat = self::healthy();
        $nama = implode(' dan ', array_column($bermasalah, 'name'));

        if ($sehat === []) {
            return $nama . ' sedang bermasalah. Silakan coba beberapa saat lagi.';
        }

        return $nama . ' sedang bermasalah. Silakan gunakan '
            . implode(' atau ', array_column($sehat, 'name')) . ' untuk sementara.';
    }

    public static function isEnabled(?string $code): bool
    {
        return $code !== null && array_key_exists($code, self::enabled());
    }

    /**
     * Saluran yang dipilih user, atau saluran default kalau pilihannya tidak
     * valid / tidak aktif. Mengembalikan null hanya kalau semua saluran mati.
     */
    public static function resolve(?string $code): ?string
    {
        $enabled = self::enabled();

        if ($enabled === []) {
            return null;
        }

        // Pilihan user tetap dihormati, termasuk saluran yang ditandai
        // bermasalah - siapa tahu sudah pulih dan dia mau mencoba.
        if ($code !== null && isset($enabled[$code])) {
            return $code;
        }

        // Yang TIDAK dipilih sendiri diarahkan ke saluran sehat, supaya saat
        // gangguan user tidak jatuh ke saluran yang sedang rusak.
        $sehat = self::healthy() ?: $enabled;

        $default = (string) config('deposit.default_channel');

        return isset($sehat[$default]) ? $default : array_key_first($sehat);
    }

    /**
     * Label saluran untuk ditampilkan, termasuk deposit warisan BayarPro yang
     * saluranya sudah tidak aktif lagi.
     */
    public static function label(?string $code): string
    {
        return match ($code) {
            self::BANKPAY => (string) config('deposit.channels.bankpay.name', 'Saluran Pembayaran 1'),
            self::QRIS_STATIS => (string) config('deposit.channels.qris_statis.name', 'Saluran Pembayaran 2'),
            'bayarpro' => 'BayarPro (arsip)',
            default => '-',
        };
    }
}
