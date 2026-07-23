<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BayarProService
{
    protected string $baseUrl;
    protected string $apiKey;
    protected string $secretKey;

    /**
     * Kanal deposit yang didukung BayarPro.
     */
    public const CHANNELS = ['QRIS', 'DANA'];

    public function __construct()
    {
        $this->baseUrl = rtrim((string) config('services.bayarpro.base_url'), '/');
        $this->apiKey = (string) config('services.bayarpro.api_key');
        $this->secretKey = (string) config('services.bayarpro.secret_key');
    }

    /**
     * Header wajib untuk setiap request.
     */
    protected function headers(array $extra = []): array
    {
        return array_merge([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'X-Api-Key' => $this->apiKey,
            'X-Secret-Key' => $this->secretKey,
        ], $extra);
    }

    /**
     * Buat invoice tagihan (deposit).
     *
     * @param  array{amount:int, merchant_ref_id:string, channel:string, customer_name?:string, idempotency_key?:string}  $data
     */
    public function createInvoice(array $data): array
    {
        $channel = strtoupper((string) ($data['channel'] ?? 'QRIS'));

        if (!in_array($channel, self::CHANNELS, true)) {
            return [
                'success' => false,
                'message' => 'Kanal pembayaran tidak didukung: ' . $channel,
                'response' => [],
            ];
        }

        $payload = [
            'payment_channel' => $channel,
            'amount' => (int) $data['amount'],
            'merchant_ref_id' => (string) $data['merchant_ref_id'],
            'customer_name' => $data['customer_name'] ?? 'Customer',
        ];

        $headers = $this->headers();

        if (!empty($data['idempotency_key'])) {
            $headers['X-Idempotency-Key'] = (string) $data['idempotency_key'];
        }

        try {
            $response = Http::timeout(30)
                ->withHeaders($headers)
                ->post($this->baseUrl . '/payment/create', $payload);
        } catch (\Throwable $e) {
            Log::error('BayarPro create invoice exception', [
                'message' => $e->getMessage(),
                'payload' => $payload,
            ]);

            return [
                'success' => false,
                'message' => 'Gagal menghubungkan ke BayarPro',
                'response' => [],
            ];
        }

        $json = $response->json();

        Log::info('BayarPro create invoice response', [
            'http_status' => $response->status(),
            'payload' => $payload,
            'response' => $json,
        ]);

        if (!$response->successful() || !is_array($json) || empty($json['success'])) {
            return [
                'success' => false,
                'message' => is_array($json)
                    ? ($json['message'] ?? 'Gagal membuat invoice BayarPro')
                    : 'Response BayarPro tidak valid',
                'response' => is_array($json) ? $json : ['body' => $response->body()],
            ];
        }

        return [
            'success' => true,
            'message' => $json['message'] ?? 'OK',
            'data' => $json['data'] ?? [],
            'response' => $json,
        ];
    }

    /**
     * Cek status transaksi berdasarkan reference_id BayarPro.
     */
    public function checkStatus(string $referenceId): array
    {
        try {
            $response = Http::timeout(20)
                ->withHeaders($this->headers())
                ->post($this->baseUrl . '/payment/status', [
                    'reference_id' => $referenceId,
                ]);
        } catch (\Throwable $e) {
            Log::error('BayarPro check status exception', [
                'message' => $e->getMessage(),
                'reference_id' => $referenceId,
            ]);

            return ['success' => false, 'response' => []];
        }

        $json = $response->json();

        return [
            'success' => $response->successful() && is_array($json) && !empty($json['success']),
            'data' => is_array($json) ? ($json['data'] ?? []) : [],
            'response' => is_array($json) ? $json : [],
        ];
    }

    /**
     * Cek saldo merchant (mode menyesuaikan API key).
     */
    public function getBalance(): array
    {
        try {
            $response = Http::timeout(20)
                ->withHeaders($this->headers())
                ->get($this->baseUrl . '/balance');
        } catch (\Throwable $e) {
            Log::error('BayarPro balance exception', ['message' => $e->getMessage()]);

            return ['success' => false, 'response' => []];
        }

        $json = $response->json();

        return [
            'success' => $response->successful() && is_array($json) && !empty($json['success']),
            'data' => is_array($json) ? ($json['data'] ?? []) : [],
            'response' => is_array($json) ? $json : [],
        ];
    }

    /**
     * Verifikasi HMAC SHA256 signature webhook.
     *
     * @param  string  $rawBody           Raw request body (php://input)
     * @param  string  $incomingSignature Nilai header X-Bayarpro-Signature
     */
    public function verifyWebhookSignature(string $rawBody, string $incomingSignature): bool
    {
        if ($incomingSignature === '' || $this->secretKey === '') {
            return false;
        }

        $expected = hash_hmac('sha256', $rawBody, $this->secretKey);

        return hash_equals($expected, $incomingSignature);
    }
}
