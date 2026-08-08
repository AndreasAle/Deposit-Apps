<?php

namespace Tests\Unit;

use App\Services\WithdrawalFee;
use App\Services\WithdrawWindow;
use Carbon\CarbonImmutable;
use Tests\TestCase;

/**
 * Jam layanan penarikan dan biaya admin.
 *
 * Zona waktu diuji secara khusus karena server berjalan pada UTC sementara
 * jam yang dimaksud adalah WIB - salah tafsir 7 jam berarti penarikan terbuka
 * tepat saat tidak ada admin yang memantau.
 */
class WithdrawWindowTest extends TestCase
{
    private function atur(string $mulai = '09:00', string $selesai = '21:00'): void
    {
        config([
            'withdraw.hours.enabled' => true,
            'withdraw.hours.start' => $mulai,
            'withdraw.hours.end' => $selesai,
            'withdraw.hours.timezone' => 'Asia/Jakarta',
            'withdraw.hours.label' => 'WIB',
        ]);
    }

    /** Waktu ditulis dalam UTC, persis seperti jam server. */
    private function utc(string $jam): CarbonImmutable
    {
        return CarbonImmutable::parse('2026-08-07 ' . $jam, 'UTC');
    }

    public function test_jam_dihitung_dalam_wib_bukan_zona_server(): void
    {
        $this->atur();

        // 02:00 UTC = 09:00 WIB -> tepat saat dibuka.
        $this->assertTrue(WithdrawWindow::isOpen($this->utc('02:00')));

        // 01:59 UTC = 08:59 WIB -> masih tutup.
        $this->assertFalse(WithdrawWindow::isOpen($this->utc('01:59')));

        // 14:00 UTC = 21:00 WIB -> tepat tutup.
        $this->assertFalse(WithdrawWindow::isOpen($this->utc('14:00')));

        // 13:59 UTC = 20:59 WIB -> masih buka.
        $this->assertTrue(WithdrawWindow::isOpen($this->utc('13:59')));
    }

    public function test_tengah_malam_wib_tertutup(): void
    {
        $this->atur();

        // 17:00 UTC = 00:00 WIB
        $this->assertFalse(WithdrawWindow::isOpen($this->utc('17:00')));
    }

    public function test_jam_yang_melewati_tengah_malam(): void
    {
        $this->atur('21:00', '03:00');

        // 15:00 UTC = 22:00 WIB -> di dalam rentang malam.
        $this->assertTrue(WithdrawWindow::isOpen($this->utc('15:00')));

        // 05:00 UTC = 12:00 WIB -> siang, di luar rentang.
        $this->assertFalse(WithdrawWindow::isOpen($this->utc('05:00')));
    }

    public function test_kalau_dimatikan_selalu_terbuka(): void
    {
        $this->atur();
        config(['withdraw.hours.enabled' => false]);

        $this->assertTrue(WithdrawWindow::isOpen($this->utc('17:00')));
    }

    public function test_label_jam_layanan(): void
    {
        $this->atur();

        $this->assertSame('09:00 - 21:00 WIB', WithdrawWindow::label());
        $this->assertStringContainsString('09:00 - 21:00 WIB', WithdrawWindow::pesanTutup());
    }

    // ---------------------------------------------------------------- biaya

    public function test_biaya_admin_lima_persen(): void
    {
        config(['withdraw.fee_percent' => 5]);

        // Nominal dari screenshot owner: dulu dipotong Rp 7.800 mati.
        $this->assertSame(7500, WithdrawalFee::hitung(150000));
        $this->assertSame(142500, WithdrawalFee::bersih(150000));

        $this->assertSame(2500, WithdrawalFee::hitung(50000));
        $this->assertSame(47500, WithdrawalFee::bersih(50000));

        $this->assertSame('5%', WithdrawalFee::label());
    }

    public function test_biaya_mengikuti_config(): void
    {
        config(['withdraw.fee_percent' => 2.5]);

        $this->assertSame(2500, WithdrawalFee::hitung(100000));
        $this->assertSame('2,5%', WithdrawalFee::label());
    }
}
