<?php

namespace Tests\Unit;

use App\Services\DepositChannels;
use Tests\TestCase;

/**
 * Penandaan saluran bermasalah dipakai justru saat keadaan sedang genting -
 * gateway tumbang dan user tetap mencoba deposit. Perilakunya harus pasti:
 * user diarahkan ke saluran sehat, tapi tidak pernah dikunci dari pilihannya
 * sendiri.
 */
class DepositChannelsTest extends TestCase
{
    private function atur(bool $bankpayRusak = false, bool $qrisRusak = false): void
    {
        config([
            'deposit.notice' => null,
            'deposit.default_channel' => 'bankpay',
            'deposit.channels.bankpay.enabled' => true,
            'deposit.channels.bankpay.name' => 'Saluran Pembayaran 1',
            'deposit.channels.bankpay.degraded' => $bankpayRusak,
            'deposit.channels.qris_statis.enabled' => true,
            'deposit.channels.qris_statis.name' => 'Saluran Pembayaran 2',
            'deposit.channels.qris_statis.degraded' => $qrisRusak,
        ]);
    }

    public function test_default_berpindah_ke_saluran_sehat_saat_ada_gangguan(): void
    {
        $this->atur(bankpayRusak: true);

        $this->assertSame(DepositChannels::QRIS_STATIS, DepositChannels::resolve(null));
    }

    public function test_pilihan_user_tetap_dihormati_walau_saluran_bermasalah(): void
    {
        $this->atur(bankpayRusak: true);

        // Siapa tahu sudah pulih dan user mau mencoba - jangan dikunci.
        $this->assertSame(DepositChannels::BANKPAY, DepositChannels::resolve(DepositChannels::BANKPAY));
    }

    public function test_tanpa_gangguan_default_tetap_seperti_config(): void
    {
        $this->atur();

        $this->assertSame(DepositChannels::BANKPAY, DepositChannels::resolve(null));
        $this->assertNull(DepositChannels::notice());
    }

    public function test_pengumuman_disusun_sendiri_dan_menyebut_saluran_pengganti(): void
    {
        $this->atur(bankpayRusak: true);

        $pengumuman = DepositChannels::notice();

        $this->assertStringContainsString('Saluran Pembayaran 1', $pengumuman);
        $this->assertStringContainsString('Saluran Pembayaran 2', $pengumuman);
    }

    public function test_pengumuman_manual_mengalahkan_yang_otomatis(): void
    {
        $this->atur(bankpayRusak: true);
        config(['deposit.notice' => 'Deposit ditutup sampai jam 8 pagi.']);

        $this->assertSame('Deposit ditutup sampai jam 8 pagi.', DepositChannels::notice());
    }

    public function test_semua_saluran_bermasalah_tidak_menyarankan_apa_apa(): void
    {
        $this->atur(bankpayRusak: true, qrisRusak: true);

        $this->assertStringContainsString('coba beberapa saat lagi', DepositChannels::notice());

        // Tetap ada saluran yang bisa dipilih; jangan sampai deposit mati total.
        $this->assertNotNull(DepositChannels::resolve(null));
    }
}
