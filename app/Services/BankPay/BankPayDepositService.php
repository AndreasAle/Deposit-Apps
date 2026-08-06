<?php

namespace App\Services\BankPay;

use App\Models\Deposit;
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
