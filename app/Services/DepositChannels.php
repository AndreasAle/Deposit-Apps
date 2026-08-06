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
     * @return array<string, array{code:string, name:string, desc:string, auto_confirm:bool}>
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
            ];
        }

        return $out;
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

        if ($code !== null && isset($enabled[$code])) {
            return $code;
        }

        $default = (string) config('deposit.default_channel');

        return isset($enabled[$default]) ? $default : array_key_first($enabled);
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
