<?php

namespace App\Services\BankPay;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;

/**
 * Dasar bersama untuk semua panggilan API BankPay.
 *
 * Protokol BankPay (lihat dokumentasi resmi):
 *   - Semua request HTTP POST dengan Content-Type
 *     application/x-www-form-urlencoded;charset=utf-8 (FORM POST, bukan JSON).
 *   - Semua respons berbentuk JSON.
 *   - Tanda tangan MD5: parameter TIDAK KOSONG diurutkan menaik berdasarkan
 *     kode ASCII nama parameternya, digabung jadi "k1=v1&k2=v2", ditambahi
 *     "&key=<kunci pedagang>", di-MD5, lalu dijadikan HURUF BESAR.
 *   - Field tanda tangan itu sendiri tidak pernah ikut ditandatangani.
 */
abstract class BankPayClient
{
    protected string $baseUrl;
    protected string $memberId;
    protected string $key;
    protected string $currency;
    protected string $bankCode;

    public function __construct()
    {
        $this->baseUrl = rtrim((string) config('services.bankpay.base_url'), '/');
        $this->memberId = trim((string) config('services.bankpay.member_id'));
        $this->key = trim((string) config('services.bankpay.key'));
        $this->currency = strtoupper((string) config('services.bankpay.currency', 'IDR'));
        $this->bankCode = (string) config('services.bankpay.bank_code', 'bank');
    }

    public function isConfigured(): bool
    {
        return $this->memberId !== '' && $this->key !== '';
    }

    public function memberId(): string
    {
        return $this->memberId;
    }

    /**
     * Hitung tanda tangan MD5 untuk sekumpulan parameter.
     *
     * @param  array<string, mixed>  $params
     * @param  array<int, string>    $except  Nama field yang tidak ikut ditandatangani
     */
    public function sign(array $params, array $except = []): string
    {
        $except = array_merge(['sign', 'pay_md5sign', 'return_type'], $except);

        $signable = [];

        foreach ($params as $name => $value) {
            if (in_array($name, $except, true)) {
                continue;
            }

            // "parameter yang tidak kosong" — null, string kosong, dan array
            // dibuang. Angka 0 tetap ikut karena bukan string kosong.
            if ($value === null || $value === '' || is_array($value)) {
                continue;
            }

            $signable[$name] = (string) $value;
        }

        // Urutan ASCII menaik atas NAMA parameter (peka huruf besar/kecil).
        ksort($signable, SORT_STRING);

        $pairs = [];

        foreach ($signable as $name => $value) {
            $pairs[] = $name . '=' . $value;
        }

        return strtoupper(md5(implode('&', $pairs) . '&key=' . $this->key));
    }

    /**
     * Verifikasi tanda tangan notifikasi yang masuk dari BankPay.
     *
     * @param  array<string, mixed>  $params  Seluruh parameter notifikasi apa adanya
     */
    public function verifyNotification(array $params): bool
    {
        $incoming = strtoupper(trim((string) ($params['sign'] ?? '')));

        if ($incoming === '' || !$this->isConfigured()) {
            return false;
        }

        return hash_equals($this->sign($params), $incoming);
    }

    /**
     * Format nominal sesuai ketentuan BankPay: satuan rupiah, dua desimal.
     * Contoh: 50000 -> "50000.00"
     */
    public function money(float|int $amount): string
    {
        return number_format((float) $amount, 2, '.', '');
    }

    /**
     * Baca nominal dari respons/notifikasi BankPay ("100.00" -> 100.0).
     */
    public function parseMoney(mixed $value): float
    {
        return (float) str_replace(',', '', (string) $value);
    }

    /**
     * URL notifikasi server untuk deposit (dipanggil BankPay, wajib balas "OK").
     */
    protected function depositNotifyUrl(): string
    {
        return (string) (config('services.bankpay.deposit_notify_url')
            ?: route('payment.bankpay.deposit.notify'));
    }

    /**
     * URL tempat user dikembalikan setelah menutup kasir BankPay.
     */
    protected function depositReturnUrl(\App\Models\Deposit $deposit): string
    {
        $configured = (string) config('services.bankpay.deposit_return_url', '');

        return $configured !== ''
            ? $configured
            : route('deposit.invoice', $deposit->id);
    }

    /**
     * URL notifikasi server untuk penarikan (pembayaran atas nama).
     */
    protected function payoutNotifyUrl(): string
    {
        return (string) (config('services.bankpay.payout_notify_url')
            ?: route('payment.bankpay.payout.notify'));
    }

    /**
     * Kirim FORM POST bertanda tangan ke BankPay.
     *
     * @param  array<string, mixed>  $params  Sudah termasuk field tanda tangan
     * @return array{ok:bool, body:array<string,mixed>, raw:string, http:int}
     */
    protected function post(string $path, array $params, int $timeout = 30): array
    {
        if (!$this->isConfigured()) {
            throw new RuntimeException('BankPay belum dikonfigurasi. Isi BANKPAY_MEMBER_ID dan BANKPAY_KEY di .env.');
        }

        $url = $this->baseUrl . '/' . ltrim($path, '/');

        Log::info('BankPay request', [
            'url' => $url,
            'params' => $this->mask($params),
        ]);

        try {
            $response = Http::timeout($timeout)
                ->withHeaders(['Accept' => 'application/json'])
                ->asForm()
                ->post($url, $params);
        } catch (\Throwable $e) {
            Log::error('BankPay request exception', [
                'url' => $url,
                'message' => $e->getMessage(),
            ]);

            throw new RuntimeException('Gagal menghubungi payment gateway: ' . $e->getMessage());
        }

        $raw = $response->body();
        $body = $response->json();

        if (!is_array($body)) {
            $body = [];
        }

        Log::info('BankPay response', [
            'url' => $url,
            'http' => $response->status(),
            'body' => $body ?: mb_substr($raw, 0, 500),
        ]);

        return [
            'ok' => $response->successful(),
            'body' => $body,
            'raw' => $raw,
            'http' => $response->status(),
        ];
    }

    /**
     * Ambil pesan error dari respons BankPay.
     *
     * Formatnya tidak seragam: kegagalan umum memakai {status:0, msg:"..."},
     * sementara endpoint lain memakai returncode + msg/trade_msg.
     */
    protected function errorMessage(array $body, string $fallback): string
    {
        foreach (['msg', 'trade_msg', 'message'] as $field) {
            $value = $body[$field] ?? null;

            if (is_string($value) && trim($value) !== '') {
                return trim($value);
            }
        }

        return $fallback;
    }

    /**
     * Samarkan data sensitif sebelum masuk log.
     *
     * @param  array<string, mixed>  $params
     * @return array<string, mixed>
     */
    protected function mask(array $params): array
    {
        foreach (['cardnumber', 'vpa', 'accountno', 'mobile'] as $field) {
            if (!isset($params[$field])) {
                continue;
            }

            $value = (string) $params[$field];

            $params[$field] = strlen($value) > 6
                ? substr($value, 0, 3) . str_repeat('*', max(strlen($value) - 6, 3)) . substr($value, -3)
                : '***';
        }

        return $params;
    }
}
