<?php

namespace Tests\Unit;

use App\Services\QrisDinamisService;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class QrisDinamisServiceTest extends TestCase
{
    private const STATIS = '00020101021126570011ID.DANA.WWW011893600915303371673602090337167360303UMI51440014ID.CO.QRIS.WWW0215ID10265623758930303UMI5204737253033605802ID5906Conweb6014Kota Palembang61053016163045C12';

    private QrisDinamisService $svc;

    protected function setUp(): void
    {
        parent::setUp();
        $this->svc = new QrisDinamisService();
    }

    /**
     * Payload acuan ini diambil dari implementasi Python yang sudah dipakai
     * membayar sungguhan (transaksi Rp2.000 berhasil via BCA).
     */
    public function test_menghasilkan_payload_identik_dengan_acuan(): void
    {
        $kasus = [
            25000 => '00020101021226570011ID.DANA.WWW011893600915303371673602090337167360303UMI51440014ID.CO.QRIS.WWW0215ID10265623758930303UMI5204737253033605405250005802ID5906Conweb6014Kota Palembang6105301616304B25F',
            2500 => '00020101021226570011ID.DANA.WWW011893600915303371673602090337167360303UMI51440014ID.CO.QRIS.WWW0215ID10265623758930303UMI520473725303360540425005802ID5906Conweb6014Kota Palembang6105301616304A83C',
            3500 => '00020101021226570011ID.DANA.WWW011893600915303371673602090337167360303UMI51440014ID.CO.QRIS.WWW0215ID10265623758930303UMI520473725303360540435005802ID5906Conweb6014Kota Palembang6105301616304EC4F',
        ];

        foreach ($kasus as $nominal => $harapan) {
            $this->assertSame($harapan, $this->svc->toDynamic(self::STATIS, $nominal));
        }
    }

    public function test_menandai_qr_sebagai_dinamis(): void
    {
        $out = $this->svc->toDynamic(self::STATIS, 50123);

        // tag 01 length 02 value 12 -> dinamis / sekali pakai
        $this->assertStringStartsWith('000201010212', $out);
    }

    public function test_menyisipkan_nominal_di_tag_54(): void
    {
        $this->assertStringContainsString('540550123', $this->svc->toDynamic(self::STATIS, 50123));
        $this->assertStringContainsString('54045000', $this->svc->toDynamic(self::STATIS, 5000));
    }

    public function test_crc_valid_untuk_banyak_nominal(): void
    {
        foreach ([50001, 50999, 100000, 1500250, 10000999] as $nominal) {
            $out = $this->svc->toDynamic(self::STATIS, $nominal);

            $this->assertSame('6304', substr($out, -8, 4), "tag CRC hilang untuk {$nominal}");

            // Bangun ulang QR dinamis dari hasilnya: CRC harus tetap sama.
            $ulang = $this->svc->toDynamic($out, $nominal);
            $this->assertSame($out, $ulang, "tidak idempoten untuk {$nominal}");
        }
    }

    public function test_menolak_nominal_nol_atau_negatif(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->svc->toDynamic(self::STATIS, 0);
    }

    public function test_menolak_qris_statis_kosong(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->svc->toDynamic('   ', 50000);
    }

    public function test_menolak_qris_rusak(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->svc->toDynamic('000201010211abcdefgh', 50000);
    }

    public function test_validasi_statis(): void
    {
        $this->assertTrue($this->svc->isValidStatic(self::STATIS));
        $this->assertFalse($this->svc->isValidStatic(null));
        $this->assertFalse($this->svc->isValidStatic(''));
        $this->assertFalse($this->svc->isValidStatic('bukan qris sama sekali'));
    }
}
