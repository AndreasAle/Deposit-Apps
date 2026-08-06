<?php

namespace Tests\Unit;

use App\Services\BankPay\BankPayDepositService;
use App\Services\BankPay\BankPayPayoutService;
use Tests\TestCase;

/**
 * Tanda tangan MD5 adalah satu-satunya hal yang membuat gateway menerima
 * permintaan kita dan membuat kita percaya notifikasi mereka. Kalau aturannya
 * meleset satu langkah saja (urutan, huruf besar, field kosong), deposit dan
 * penarikan berhenti total — jadi diuji langsung terhadap contoh resmi pada
 * dokumentasi BankPay.
 */
class BankPaySignatureTest extends TestCase
{
    /**
     * Contoh "koleksi daring" dari dokumentasi.
     *
     * Catatan: pasangan contoh di dokumentasi tidak konsisten — teks sandi
     * yang mereka cantumkan (D7B01D41...) bukan MD5 dari string asli yang
     * mereka cantumkan sendiri, dengan spasi mentah maupun ter-encode. Yang
     * dipakai sebagai acuan di sini adalah bagian yang otoritatif: ATURAN
     * penyusunan dan STRING ASLI-nya. Jadi yang diuji adalah bahwa sign()
     * menyusun string persis seperti contoh — urutan ASCII, spasi apa adanya,
     * dan &key= di akhir.
     */
    public function test_tanda_tangan_pembayaran_menyusun_string_seperti_contoh_dokumentasi(): void
    {
        $key = '2c461fdecf75921f68482675206204c3';

        config(['services.bankpay.key' => $key]);

        $stringAsli = 'pay_amount=0.08&pay_applydate=2018-06-06 20:10:00&pay_bankcode=bank'
            . '&pay_callbackurl=https://google.com/page.php&pay_memberid=10002'
            . '&pay_notifyurl=https://google.com/server.php'
            . '&pay_orderid=E20181208122100208716&key=' . $key;

        $sign = app(BankPayDepositService::class)->sign([
            // Sengaja diacak: pengurutan ASCII yang harus merapikannya.
            'pay_orderid' => 'E20181208122100208716',
            'pay_memberid' => '10002',
            'pay_amount' => '0.08',
            'pay_notifyurl' => 'https://google.com/server.php',
            'pay_applydate' => '2018-06-06 20:10:00',
            'pay_callbackurl' => 'https://google.com/page.php',
            'pay_bankcode' => 'bank',
        ]);

        $this->assertSame(strtoupper(md5($stringAsli)), $sign);
    }

    /** Contoh "pembayaran atas nama" jalur kartu bank dari dokumentasi. */
    public function test_tanda_tangan_pencairan_kartu_bank_cocok_dengan_contoh_dokumentasi(): void
    {
        $key = 'akjfuhao835rikshet73tkg83234iura';

        config(['services.bankpay.key' => $key]);

        $params = [
            'accountname' => 'NndksT',
            'amount' => '500',
            'bankcode' => 'bank',
            'bankname' => 'abcdefg',
            'cardnumber' => '8111293332',
            'email' => 'WFqHpr@gmail.com',
            'bankno' => 'IBKL0009999',
            'memberid' => '10002',
            'mobile' => '8111293332',
            'notifyurl' => 'google.com/demo/page.php',
            'orderid' => 'EE20211008133148160889',
        ];

        // Dokumentasi mencantumkan string aslinya persis seperti ini.
        $expected = strtoupper(md5(
            'accountname=NndksT&amount=500&bankcode=bank&bankname=abcdefg'
            . '&bankno=IBKL0009999&cardnumber=8111293332&email=WFqHpr@gmail.com'
            . '&memberid=10002&mobile=8111293332&notifyurl=google.com/demo/page.php'
            . '&orderid=EE20211008133148160889&key=' . $key
        ));

        $this->assertSame($expected, app(BankPayPayoutService::class)->sign($params));
    }

    /** Field tanda tangan dan return_type tidak pernah ikut ditandatangani. */
    public function test_field_tanda_tangan_dan_return_type_dikecualikan(): void
    {
        config(['services.bankpay.key' => 'rahasia']);

        $service = app(BankPayDepositService::class);

        $bersih = $service->sign(['memberid' => '10002', 'orderid' => 'A1']);

        $kotor = $service->sign([
            'memberid' => '10002',
            'orderid' => 'A1',
            'sign' => 'NILAI-LAMA',
            'pay_md5sign' => 'NILAI-LAMA',
            'return_type' => 'json',
        ]);

        $this->assertSame($bersih, $kotor);
    }

    /** Parameter kosong dibuang, tapi angka 0 tetap ikut ditandatangani. */
    public function test_parameter_kosong_dibuang_tapi_nol_tetap_ikut(): void
    {
        config(['services.bankpay.key' => 'rahasia']);

        $service = app(BankPayDepositService::class);

        $this->assertSame(
            $service->sign(['a' => '1']),
            $service->sign(['a' => '1', 'b' => '', 'c' => null]),
            'Parameter kosong seharusnya tidak mengubah tanda tangan.'
        );

        $this->assertNotSame(
            $service->sign(['a' => '1']),
            $service->sign(['a' => '1', 'b' => 0]),
            'Angka 0 bukan nilai kosong dan harus ikut ditandatangani.'
        );
    }

    /** Notifikasi dengan tanda tangan salah harus ditolak. */
    public function test_verifikasi_notifikasi(): void
    {
        config([
            'services.bankpay.key' => 'rahasia',
            'services.bankpay.member_id' => '10002',
        ]);

        $service = app(BankPayDepositService::class);

        $params = [
            'memId' => '10002',
            'orderNo' => 'DEP20260806120000ABCDEF',
            'transId' => 'TRX1',
            'payAmount' => '50000.00',
            'datetime' => '20260806120500',
            'code' => '1',
        ];

        $params['sign'] = $service->sign($params);

        $this->assertTrue($service->verifyNotification($params));

        // Nominal diubah setelah ditandatangani -> harus ketahuan.
        $this->assertFalse($service->verifyNotification(
            array_merge($params, ['payAmount' => '999000.00'])
        ));

        $this->assertFalse($service->verifyNotification(
            array_merge($params, ['sign' => ''])
        ));
    }
}
