<?php

namespace App\Services;

use App\Models\Withdrawal;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class BayarProPayoutService
{
    protected string $baseUrl;
    protected string $apiKey;
    protected string $secretKey;

    /**
     * Bank / e-wallet tujuan payout yang didukung BayarPro.
     * Key = provider di sistem kita, value = kode bank_target BayarPro.
     */
    public const BANK_TARGETS = [
        'BCA' => 'BCA',
        'BRI' => 'BRI',
        'MANDIRI' => 'MANDIRI',
        'BNI' => 'BNI',
        'PERMATA' => 'PERMATA',
        'CIMB' => 'CIMB',
        'DANAMON' => 'DANAMON',
        'DANA' => 'DANA',
        'OVO' => 'OVO',
    ];

    public function __construct()
    {
        $this->baseUrl = rtrim((string) config('services.bayarpro.base_url'), '/');
        $this->apiKey = (string) config('services.bayarpro.api_key');
        $this->secretKey = (string) config('services.bayarpro.secret_key');
    }

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
     * Kirim payout ke BayarPro.
     */
    public function createPayout(Withdrawal $withdrawal): array
    {
        $withdrawal->loadMissing(['user', 'payoutAccount']);

        $account = $withdrawal->payoutAccount;

        if (!$account) {
            throw new RuntimeException('Akun payout tidak ditemukan.');
        }

        $provider = strtoupper(trim((string) $account->provider));

        if (!isset(self::BANK_TARGETS[$provider])) {
            throw new RuntimeException('Tujuan payout tidak didukung BayarPro: ' . $provider);
        }

        $money = (int) round((float) $withdrawal->net_amount);

        if ($money <= 0) {
            throw new RuntimeException('Nominal payout tidak valid.');
        }

        $payload = [
            'account_name' => trim((string) $account->account_name),
            'account_number' => preg_replace('/\s+/', '', (string) $account->account_number),
            'bank_target' => self::BANK_TARGETS[$provider],
            'amount' => $money,
        ];

        // Idempotency key mencegah dobel pencairan kalau request timeout lalu di-retry.
        $headers = $this->headers([
            'X-Idempotency-Key' => 'wd_' . $withdrawal->order_id,
        ]);

        Log::info('BayarPro payout request', [
            'withdrawal_id' => $withdrawal->id,
            'payload' => $this->maskPayload($payload),
        ]);

        try {
            $response = Http::timeout(30)
                ->withHeaders($headers)
                ->post($this->baseUrl . '/payout/create', $payload);
        } catch (\Throwable $e) {
            Log::error('BayarPro payout exception', [
                'withdrawal_id' => $withdrawal->id,
                'message' => $e->getMessage(),
            ]);

            throw new RuntimeException('Gagal menghubungkan ke BayarPro: ' . $e->getMessage());
        }

        $body = $response->json();

        if (!is_array($body)) {
            $body = ['raw' => $response->body()];
        }

        Log::info('BayarPro payout response', [
            'withdrawal_id' => $withdrawal->id,
            'http_status' => $response->status(),
            'body' => $body,
        ]);

        if (!$response->successful() || empty($body['success'])) {
            throw new RuntimeException(
                $body['message'] ?? ('Payout BayarPro gagal. HTTP ' . $response->status())
            );
        }

        return $body;
    }

    /**
     * Cek status payout (best-effort — endpoint tidak terdokumentasi resmi).
     * Dipakai poller cadangan karena webhook payout BayarPro tidak andal.
     * Mengembalikan success=false bila endpoint tidak tersedia -> poller skip.
     */
    public function checkPayoutStatus(string $payoutId): array
    {
        try {
            $response = Http::timeout(20)
                ->withHeaders($this->headers())
                ->post($this->baseUrl . '/payout/status', [
                    'payout_id' => $payoutId,
                    'reference_id' => $payoutId,
                ]);
        } catch (\Throwable $e) {
            return ['success' => false, 'data' => [], 'response' => []];
        }

        $json = $response->json();

        return [
            'success' => $response->successful() && is_array($json) && !empty($json['success']),
            'data' => is_array($json) ? ($json['data'] ?? []) : [],
            'response' => is_array($json) ? $json : [],
        ];
    }

    /**
     * Verifikasi HMAC SHA256 signature webhook payout.
     */
    public function verifyWebhookSignature(string $rawBody, string $incomingSignature): bool
    {
        if ($incomingSignature === '' || $this->secretKey === '') {
            return false;
        }

        return hash_equals(hash_hmac('sha256', $rawBody, $this->secretKey), $incomingSignature);
    }

    private function maskPayload(array $payload): array
    {
        if (isset($payload['account_number'])) {
            $num = (string) $payload['account_number'];
            $payload['account_number'] = strlen($num) > 6
                ? substr($num, 0, 3) . str_repeat('*', max(strlen($num) - 6, 3)) . substr($num, -3)
                : '***';
        }

        return $payload;
    }
}
