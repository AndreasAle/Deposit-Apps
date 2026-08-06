<?php

namespace Tests\Unit;

use App\Services\BankPay\BankPayDepositService;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

/**
 * Pemungutan QR dari halaman kasir BankPay.
 *
 * Ini menyandarkan diri pada bentuk halaman milik orang lain, jadi yang paling
 * penting diuji bukan keberhasilannya - melainkan bahwa KEGAGALANNYA selalu
 * berakhir `null` dengan tenang, tidak pernah melempar error. Deposit tidak
 * boleh gagal dibuat hanya karena QR-nya tidak bisa dipungut.
 */
class BankPayQrScrapeTest extends TestCase
{
    private function service(): BankPayDepositService
    {
        config([
            'services.bankpay.key' => 'rahasia',
            'services.bankpay.member_id' => '13315',
        ]);

        return app(BankPayDepositService::class);
    }

    public function test_string_emv_diutamakan_kalau_ada(): void
    {
        $emv = '00020101021226570011ID.DANA.WWW01189360091530337167360209033716736'
            . '0303UMI51440014ID.CO.QRIS.WWW0215ID102656237589303035204737253033605802ID63041A2B';

        Http::fake(['*' => Http::response('<html><body><script>var q="' . $emv . '";</script></body></html>')]);

        $this->assertSame($emv, $this->service()->fetchQrFromCheckout('https://rc.bankpay.cfd/Pay-pay-bankpay.aspx?token=x'));
    }

    public function test_gambar_base64_dipakai_kalau_emv_tidak_ada(): void
    {
        $base64 = str_repeat('AB', 150);

        Http::fake(['*' => Http::response('<img src="data:image/png;base64,' . $base64 . '">')]);

        $hasil = $this->service()->fetchQrFromCheckout('https://rc.bankpay.cfd/Pay-pay-bankpay.aspx?token=x');

        $this->assertNotNull($hasil);
        $this->assertStringStartsWith('data:image/png;base64,', $hasil);
    }

    public function test_halaman_tanpa_qr_menghasilkan_null(): void
    {
        Http::fake(['*' => Http::response('<html><body>Halaman kasir berubah total</body></html>')]);

        $this->assertNull($this->service()->fetchQrFromCheckout('https://rc.bankpay.cfd/x'));
    }

    public function test_halaman_error_menghasilkan_null_bukan_error(): void
    {
        // Skenario nyata: ditantang Cloudflare, atau halaman kasir mati.
        Http::fake(['*' => Http::response('Forbidden', 403)]);

        $this->assertNull($this->service()->fetchQrFromCheckout('https://rc.bankpay.cfd/x'));
    }

    public function test_koneksi_gagal_menghasilkan_null_bukan_error(): void
    {
        Http::fake(fn () => throw new \Illuminate\Http\Client\ConnectionException('timeout'));

        $this->assertNull($this->service()->fetchQrFromCheckout('https://rc.bankpay.cfd/x'));
    }

    public function test_pay_url_kosong_tidak_memanggil_jaringan(): void
    {
        Http::fake();

        $this->assertNull($this->service()->fetchQrFromCheckout(null));

        Http::assertNothingSent();
    }
}
