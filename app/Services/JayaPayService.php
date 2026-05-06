<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class JayaPayService
{
    protected string $merchantCode;
    protected string $createOrderUrl;
    protected string $privateKey;
    protected string $platformPublicKey;
    protected string $notifyUrl;
    protected int $expiryPeriod;

    public function __construct()
    {
        $this->merchantCode = (string) config('services.jayapay.merchant_code');
        $this->createOrderUrl = (string) config('services.jayapay.create_order_url');
        $this->privateKey = (string) config('services.jayapay.private_key');
        $this->platformPublicKey = (string) config('services.jayapay.platform_public_key');
        $this->notifyUrl = (string) config('services.jayapay.notify_url');
        $this->expiryPeriod = (int) config('services.jayapay.expiry_period', 1440);
    }

    public function createDepositOrder(array $data): array
    {
$payload = [
    'merchantCode' => $this->merchantCode,
    'orderType' => '0',
    'orderNum' => $data['order_num'],
    'payMoney' => (string) (int) $data['amount'],
    'productDetail' => $data['product_detail'] ?? 'Deposit Saldo',
    'notifyUrl' => $this->notifyUrl,
    'dateTime' => now()->format('YmdHis'),
    'expiryPeriod' => (string) $this->expiryPeriod,
    'name' => $data['name'] ?? 'Customer',
    'email' => $data['email'] ?? 'customer@email.com',
    'phone' => $data['phone'] ?? '080000000000',
];

if (!empty($data['method']) && $data['method'] !== 'CASHIER') {
    $payload['method'] = $data['method'];
}

        $payload['sign'] = $this->makeSign($payload);

        $response = Http::timeout(30)
            ->acceptJson()
            ->asJson()
            ->post($this->createOrderUrl, $payload);

        if (!$response->successful()) {
            Log::error('JayaPay create order HTTP error', [
                'status' => $response->status(),
                'body' => $response->body(),
                'payload' => $payload,
            ]);

            return [
                'success' => false,
                'message' => 'Gagal menghubungkan ke JayaPay',
                'payload' => $payload,
                'response' => [
                    'http_status' => $response->status(),
                    'body' => $response->body(),
                ],
            ];
        }

        $json = $response->json();

        Log::info('JayaPay create order response', [
            'payload' => $payload,
            'response' => $json,
        ]);

        return [
            'success' => isset($json['platRespCode']) && $json['platRespCode'] === 'SUCCESS',
            'message' => $json['platRespMessage'] ?? 'Unknown response',
            'payload' => $payload,
            'response' => $json,
        ];
    }

    public function makeSign(array $params): string
    {
        unset($params['sign'], $params['platSign']);

        $plainText = $this->buildSignPlainText($params);

        return $this->privateEncrypt($plainText, $this->privateKey);
    }

    public function verifyCallback(array $params): bool
    {
        if (empty($params['platSign'])) {
            return false;
        }

        $platSign = $params['platSign'];
        unset($params['platSign']);

        $plainText = $this->buildSignPlainText($params);
        $decryptSign = $this->publicDecrypt($platSign, $this->platformPublicKey);

        return hash_equals($plainText, $decryptSign);
    }

    public function buildSignPlainText(array $params): string
    {
        $filtered = [];

        foreach ($params as $key => $value) {
            if ($value === null) {
                continue;
            }

            if ($value === '') {
                continue;
            }

            if (is_array($value) || is_object($value)) {
                $value = json_encode($value, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            }

            $filtered[$key] = (string) $value;
        }

        ksort($filtered, SORT_STRING);

        return implode('', array_values($filtered));
    }

    protected function privateEncrypt(string $plainText, string $privateKey): string
    {
        $pem = $this->normalizePrivateKey($privateKey);

        $key = openssl_pkey_get_private($pem);

        if (!$key) {
            throw new \RuntimeException('Private key JayaPay tidak valid.');
        }

        $details = openssl_pkey_get_details($key);
        $keySize = (int) (($details['bits'] ?? 1024) / 8);
        $maxBlock = $keySize - 11;

        $output = '';

        foreach (str_split($plainText, $maxBlock) as $chunk) {
            $encrypted = '';

            $ok = openssl_private_encrypt($chunk, $encrypted, $key, OPENSSL_PKCS1_PADDING);

            if (!$ok) {
                throw new \RuntimeException('Gagal membuat signature JayaPay.');
            }

            $output .= $encrypted;
        }

        return base64_encode($output);
    }

    protected function publicDecrypt(string $encryptedBase64, string $publicKey): string
    {
        $pem = $this->normalizePublicKey($publicKey);

        $key = openssl_pkey_get_public($pem);

        if (!$key) {
            throw new \RuntimeException('Platform public key JayaPay tidak valid.');
        }

        $details = openssl_pkey_get_details($key);
        $keySize = (int) (($details['bits'] ?? 1024) / 8);

        $encrypted = base64_decode($encryptedBase64, true);

        if ($encrypted === false) {
            return '';
        }

        $output = '';

        foreach (str_split($encrypted, $keySize) as $chunk) {
            $decrypted = '';

            $ok = openssl_public_decrypt($chunk, $decrypted, $key, OPENSSL_PKCS1_PADDING);

            if (!$ok) {
                return '';
            }

            $output .= $decrypted;
        }

        return $output;
    }

    protected function normalizePrivateKey(string $key): string
    {
        $key = trim($key);

        if (str_contains($key, 'BEGIN')) {
            return str_replace('\\n', "\n", $key);
        }

        $key = str_replace(["\r", "\n", ' '], '', $key);

        return "-----BEGIN PRIVATE KEY-----\n"
            . chunk_split($key, 64, "\n")
            . "-----END PRIVATE KEY-----\n";
    }

    protected function normalizePublicKey(string $key): string
    {
        $key = trim($key);

        if (str_contains($key, 'BEGIN')) {
            return str_replace('\\n', "\n", $key);
        }

        $key = str_replace(["\r", "\n", ' '], '', $key);

        return "-----BEGIN PUBLIC KEY-----\n"
            . chunk_split($key, 64, "\n")
            . "-----END PUBLIC KEY-----\n";
    }
}