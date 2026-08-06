<?php

namespace App\Services\BankPay;

use App\Models\Deposit;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;

/**
 * Sisi COLLECTION (deposit) dari payment gateway BankPay.
 *
 * Memakai "Koleksi daring" dengan return_type=json, sehingga yang kembali
 * adalah tautan pembayaran + isi kode QR. QR-nya dirender sendiri di halaman
 * invoice Capital Wave, jadi user tidak perlu dilempar keluar aplikasi.
 */
class BankPayDepositService extends BankPayClient
{
    public const CHANNEL = 'bankpay';

    /**
     * Buat invoice pembayaran di BankPay.
     *
     * @return array{pay_url:?string, qr_content:?string, response:array<string,mixed>}
     *
     * @throws RuntimeException kalau gateway menolak permintaan
     */
    public function createInvoice(Deposit $deposit): array
    {
        $params = [
            'pay_memberid' => $this->memberId,
            'pay_orderid' => $deposit->order_id,
            'pay_applydate' => now()->format('Y-m-d H:i:s'),
            'pay_bankcode' => $this->bankCode,
            'pay_currency' => $this->currency,
            'pay_notifyurl' => $this->depositNotifyUrl(),
            'pay_callbackurl' => $this->depositReturnUrl($deposit),
            'pay_amount' => $this->money($deposit->amount),
        ];

        $params['pay_md5sign'] = $this->sign($params);

        // Minta respons JSON supaya kita dapat payurl + qrCode, bukan redirect
        // langsung ke kasir. Field ini sengaja TIDAK ikut ditandatangani.
        $params['return_type'] = 'json';

        $result = $this->post('Pay-payment.aspx', $params);
        $body = $result['body'];

        $returnCode = (string) ($body['returncode'] ?? '');

        if (!$result['ok'] || $returnCode !== '200') {
            throw new RuntimeException(
                $this->errorMessage($body, 'Gateway menolak permintaan pembayaran (HTTP ' . $result['http'] . ').')
            );
        }

        $payUrl = $this->firstFilled($body, ['payurl', 'payUrl', 'pay_url']);

        // qrCode / qrcode dikirim dengan penamaan berbeda-beda; kalau gateway
        // tidak mengirim isi QR sama sekali, tautan pembayaran yang dipakai.
        $qrContent = $this->firstFilled($body, ['qrCode', 'qrcode', 'qr_code']);

        if ($payUrl === null && $qrContent === null) {
            throw new RuntimeException('Gateway tidak mengembalikan tautan pembayaran.');
        }

        return [
            'pay_url' => $payUrl,
            'qr_content' => $qrContent,
            'response' => $body,
        ];
    }

    /**
     * Ambil isi QR dari halaman kasir BankPay.
     *
     * BankPay tidak mengembalikan `qrCode` lewat API, padahal halaman kasir
     * mereka jelas menampilkan QRIS-nya. Fungsi ini mengambil halaman itu dan
     * memungut QR-nya supaya user bisa membayar tanpa keluar dari aplikasi.
     *
     * INI RAPUH DAN DISENGAJA BERSIFAT USAHA-TERBAIK. Kita menyandarkan diri
     * pada bentuk halaman orang lain, yang bisa berubah kapan saja tanpa
     * pemberitahuan. Karena itu:
     *   - kegagalan TIDAK PERNAH menggagalkan pembuatan deposit;
     *   - kalau nihil, alur lama (tombol ke halaman kasir) tetap jalan;
     *   - jangan pernah menjadikan ini satu-satunya cara user bisa membayar.
     *
     * Solusi sebenarnya tetap: minta BankPay mengaktifkan `qrCode` di API.
     *
     * @return string|null  String EMV QRIS, atau data URI gambar, atau null
     */
    public function fetchQrFromCheckout(?string $payUrl): ?string
    {
        if (!$payUrl) {
            return null;
        }

        try {
            $response = Http::timeout(8)
                ->withHeaders([
                    // Halaman kasir ada di balik Cloudflare; permintaan yang
                    // terlihat seperti bot besar kemungkinan ditantang.
                    'User-Agent' => 'Mozilla/5.0 (Linux; Android 13) AppleWebKit/537.36 '
                        . '(KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36',
                    'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
                    'Accept-Language' => 'id,en-US;q=0.8',
                ])
                ->get($payUrl);
        } catch (\Throwable $e) {
            Log::warning('BankPay ambil QR kasir gagal', ['message' => $e->getMessage()]);

            return null;
        }

        if (!$response->successful()) {
            Log::warning('BankPay ambil QR kasir non-200', ['http' => $response->status()]);

            return null;
        }

        $html = $response->body();

        // Prioritas: string EMV mentah. Ini yang paling berharga karena QR-nya
        // bisa kita render sendiri dengan ukuran dan gaya yang kita mau.
        if (preg_match('/\b(00020101[0-9A-Za-z.\-]{30,512}6304[0-9A-Fa-f]{4})\b/', $html, $m)) {
            return $m[1];
        }

        // Cadangan: gambar QR yang sudah jadi, tertanam sebagai data URI.
        if (preg_match('#data:image/(?:png|jpeg|gif);base64,[A-Za-z0-9+/]{200,}={0,2}#', $html, $m)) {
            return $m[0];
        }

        Log::info('BankPay halaman kasir tidak memuat QR yang dikenali', [
            'panjang_html' => strlen($html),
        ]);

        return null;
    }

    /**
     * Tanya status satu pesanan ke BankPay.
     *
     * Dipakai sebagai cadangan kalau notifikasi server tidak sampai.
     *
     * @return array{success:bool, paid:bool, amount:?float, transaction_id:?string, response:array<string,mixed>}
     */
    public function queryOrder(string $orderId): array
    {
        $blank = ['success' => false, 'paid' => false, 'amount' => null, 'transaction_id' => null, 'response' => []];

        if (!$this->isConfigured()) {
            return $blank;
        }

        $params = [
            'memberid' => $this->memberId,
            'orderid' => $orderId,
        ];

        $params['sign'] = $this->sign($params);

        try {
            $result = $this->post('Pay-Trade-query.aspx', $params, 20);
        } catch (\Throwable) {
            return $blank;
        }

        $body = $result['body'];

        // returncode "00" menandakan KUERI-nya berhasil, bukan pembayarannya.
        if (!$result['ok'] || (string) ($body['returncode'] ?? '') !== '00') {
            return array_merge($blank, ['response' => $body]);
        }

        // act_amount = jumlah yang benar-benar dibayar; itu yang jadi acuan.
        $paidAmount = $this->firstFilled($body, ['act_amount', 'amount']);

        return [
            'success' => true,
            'paid' => strtoupper((string) ($body['trade_state'] ?? '')) === 'SUCCESS',
            'amount' => $paidAmount === null ? null : $this->parseMoney($paidAmount),
            'transaction_id' => $this->firstFilled($body, ['transaction_id']),
            'response' => $body,
        ];
    }

    /**
     * @param  array<string, mixed>  $body
     * @param  array<int, string>    $keys
     */
    private function firstFilled(array $body, array $keys): ?string
    {
        foreach ($keys as $key) {
            $value = $body[$key] ?? null;

            if (is_string($value) && trim($value) !== '') {
                return trim($value);
            }

            if (is_numeric($value)) {
                return (string) $value;
            }
        }

        return null;
    }
}
