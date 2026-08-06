<?php

namespace App\Services;

use InvalidArgumentException;

/**
 * Konversi QRIS statis -> QRIS dinamis (nominal terkunci).
 *
 * Format QRIS mengikuti EMVCo: rangkaian TLV (tag 2 digit, length 2 digit,
 * value). Yang diubah:
 *   - tag 01 : "11" (statis, nominal diisi pembeli) -> "12" (dinamis, sekali pakai)
 *   - tag 54 : nominal, disisipkan tepat sebelum tag 58 (country code)
 *   - tag 63 : CRC16-CCITT dihitung ulang atas seluruh payload
 */
class QrisDinamisService
{
    /**
     * Bangun payload QRIS dinamis dengan nominal terkunci.
     *
     * @param  string  $qrisStatis  String EMV QRIS statis merchant
     * @param  int     $amount      Nominal rupiah (bulat, > 0)
     */
    public function toDynamic(string $qrisStatis, int $amount): string
    {
        $qrisStatis = trim($qrisStatis);

        if ($qrisStatis === '') {
            throw new InvalidArgumentException('QRIS statis belum dikonfigurasi (QRIS_STATIS).');
        }

        if ($amount <= 0) {
            throw new InvalidArgumentException('Nominal QRIS harus lebih dari 0.');
        }

        // Buang tag 63 (CRC lama) kalau ada.
        $body = $qrisStatis;
        if (strlen($qrisStatis) >= 8 && substr($qrisStatis, -8, 4) === '6304') {
            $body = substr($qrisStatis, 0, -8);
        }

        $fields = $this->parseTlv($body);

        // Bersihkan tag nominal/biaya lama supaya tidak dobel.
        $fields = array_values(array_filter(
            $fields,
            fn (array $f) => !in_array($f[0], ['54', '55', '56', '57'], true)
        ));

        $rebuilt = [];
        $countryIndex = null;

        foreach ($fields as $i => [$tag, $value]) {
            if ($tag === '01') {
                $value = '12'; // dinamis / sekali pakai
            }

            $rebuilt[] = [$tag, $value];

            if ($tag === '58' && $countryIndex === null) {
                $countryIndex = count($rebuilt) - 1;
            }
        }

        if ($countryIndex === null) {
            throw new InvalidArgumentException('QRIS statis tidak valid: tag 58 tidak ditemukan.');
        }

        // Sisipkan nominal tepat sebelum tag 58.
        array_splice($rebuilt, $countryIndex, 0, [['54', (string) $amount]]);

        $payload = '';
        foreach ($rebuilt as [$tag, $value]) {
            $payload .= $this->tlv($tag, $value);
        }

        $payload .= '6304';

        return $payload . $this->crc16($payload);
    }

    /**
     * Validasi ringan: string bisa di-parse dan punya tag wajib.
     */
    public function isValidStatic(?string $qris): bool
    {
        if (!$qris || strlen(trim($qris)) < 20) {
            return false;
        }

        try {
            $body = trim($qris);
            if (substr($body, -8, 4) === '6304') {
                $body = substr($body, 0, -8);
            }

            $tags = array_column($this->parseTlv($body), 0);

            return in_array('58', $tags, true) && in_array('01', $tags, true);
        } catch (\Throwable) {
            return false;
        }
    }

    private function tlv(string $tag, string $value): string
    {
        return $tag . str_pad((string) strlen($value), 2, '0', STR_PAD_LEFT) . $value;
    }

    /**
     * Pecah string EMVCo jadi list [tag, value] di level teratas.
     *
     * @return array<int, array{0:string,1:string}>
     */
    private function parseTlv(string $s): array
    {
        $out = [];
        $i = 0;
        $len = strlen($s);

        while ($i < $len) {
            if ($i + 4 > $len) {
                throw new InvalidArgumentException('QRIS rusak: header TLV terpotong.');
            }

            $tag = substr($s, $i, 2);
            $lenStr = substr($s, $i + 2, 2);

            if (!ctype_digit($lenStr)) {
                throw new InvalidArgumentException('QRIS rusak: panjang TLV bukan angka.');
            }

            $n = (int) $lenStr;

            if ($i + 4 + $n > $len) {
                throw new InvalidArgumentException('QRIS rusak: value TLV melebihi panjang string.');
            }

            $out[] = [$tag, substr($s, $i + 4, $n)];
            $i += 4 + $n;
        }

        return $out;
    }

    /**
     * CRC16-CCITT (poly 0x1021, init 0xFFFF), hasil 4 digit hex uppercase.
     */
    private function crc16(string $data): string
    {
        $crc = 0xFFFF;

        for ($i = 0, $n = strlen($data); $i < $n; $i++) {
            $crc ^= ord($data[$i]) << 8;

            for ($b = 0; $b < 8; $b++) {
                $crc = ($crc & 0x8000)
                    ? (($crc << 1) ^ 0x1021) & 0xFFFF
                    : ($crc << 1) & 0xFFFF;
            }
        }

        return strtoupper(str_pad(dechex($crc), 4, '0', STR_PAD_LEFT));
    }
}
