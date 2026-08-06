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

    /**
     * `payurl` BankPay sebenarnya cuma pembungkus ~3 KB yang menyembunyikan
     * alamat kasir asli (di domain lain) sebagai URL ter-XOR + base64, lalu
     * memuatnya di iframe lewat JavaScript. Server kita tidak menjalankan JS,
     * jadi lompatan itu harus diikuti sendiri.
     */
    public function test_mengikuti_url_kasir_yang_disamarkan_dengan_xor(): void
    {
        $emv = '00020101021226570011ID.DANA.WWW01189360091530337167360209033716736'
            . '0303UMI51440014ID.CO.QRIS.WWW0215ID102656237589303035204737253033605802ID63041A2B';

        Http::fake([
            'rc.bankpay.cfd/*' => Http::response($this->halamanPembungkus(
                'https://idr-order-server.hzpay123.com/view/qris/CI202608062246439462385'
            )),
            'idr-order-server.hzpay123.com/*' => Http::response('<div>Scan this QRIS</div><span>' . $emv . '</span>'),
        ]);

        $this->assertSame(
            $emv,
            $this->service()->fetchQrFromCheckout('https://rc.bankpay.cfd/Pay-pay-bankpay.aspx?token=x')
        );
    }

    /**
     * URL hasil dekripsi berasal dari halaman pihak lain, jadi tidak tepercaya.
     * Tanpa penjagaan, siapa pun yang bisa mengubah halaman kasir dapat
     * menyuruh server kita menembak alamat internal (SSRF).
     */
    public function test_url_tidak_aman_tidak_pernah_diikuti(): void
    {
        foreach ([
            'http://idr-order-server.hzpay123.com/view/qris/CI1',  // bukan https
            'https://localhost/view/qris/CI1',
            'https://127.0.0.1/view/qris/CI1',
            'https://192.168.1.10/admin',
        ] as $jahat) {
            Http::fake([
                'rc.bankpay.cfd/*' => Http::response($this->halamanPembungkus($jahat)),
                '*' => Http::response('<span>00020101TIDAKBOLEHTERBACA6304ABCD</span>'),
            ]);

            $this->assertNull(
                $this->service()->fetchQrFromCheckout('https://rc.bankpay.cfd/Pay-pay-bankpay.aspx?token=x'),
                'URL tidak aman seharusnya tidak diikuti: ' . $jahat
            );
        }
    }

    /** Tiruan halaman pembungkus BankPay, persis bentuk aslinya. */
    private function halamanPembungkus(string $urlAsli): string
    {
        $key = '9e3644a14da5dbea';
        $xor = '';

        for ($i = 0, $n = strlen($urlAsli); $i < $n; $i++) {
            $xor .= chr(ord($urlAsli[$i]) ^ ord($key[$i % strlen($key)]));
        }

        return '<html><body><iframe id="payframe" src="about:blank"></iframe><script>'
            . "var encryptedPayUrl = '" . base64_encode($xor) . "';"
            . "var payUrlKey = '" . $key . "';"
            . '</script></body></html>';
    }

    public function test_pay_url_kosong_tidak_memanggil_jaringan(): void
    {
        Http::fake();

        $this->assertNull($this->service()->fetchQrFromCheckout(null));

        Http::assertNothingSent();
    }
}
