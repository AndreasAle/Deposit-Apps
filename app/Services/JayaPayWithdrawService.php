<?php

namespace App\Services;

use App\Models\Withdrawal;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class JayaPayWithdrawService
{
    public function createPayout(Withdrawal $withdrawal): array
    {
        $withdrawal->loadMissing(['user', 'payoutAccount']);

        $url = config('services.jayapay.payout_url');

        if (!$url) {
            throw new RuntimeException('JayaPay payout URL belum diset.');
        }

        $payload = $this->buildPayoutPayload($withdrawal);
        $payload['sign'] = $this->makeSign($payload);

        Log::info('JayaPay withdraw request', [
            'withdrawal_id' => $withdrawal->id,
            'url' => $url,
            'payload' => $this->maskPayloadForLog($payload),
        ]);

        $response = Http::timeout(30)
            ->asJson()
            ->withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])
            ->post($url, $payload);

        $body = $response->json();

        if (!is_array($body)) {
            $body = [
                'raw' => $response->body(),
            ];
        }

        Log::info('JayaPay withdraw response', [
            'withdrawal_id' => $withdrawal->id,
            'http_status' => $response->status(),
            'body' => $body,
        ]);

        if (!$response->successful()) {
            throw new RuntimeException('Request payout JayaPay gagal. HTTP ' . $response->status());
        }

        return $body;
    }

private function buildPayoutPayload(Withdrawal $withdrawal): array
{
    $user = $withdrawal->user;
    $account = $withdrawal->payoutAccount;

    if (!$user) {
        throw new RuntimeException('User withdraw tidak ditemukan.');
    }

    if (!$account) {
        throw new RuntimeException('Akun payout tidak ditemukan.');
    }

    $provider = strtoupper(trim((string) $account->provider));
    $mapped = $this->mapPayoutMethod($provider);

    $money = (int) round((float) $withdrawal->net_amount);

    if ($money <= 0) {
        throw new RuntimeException('Nominal payout tidak valid.');
    }

    return [
        'merchantCode' => config('services.jayapay.merchant_code'),
        'orderType' => (string) config('services.jayapay.withdraw_order_type', '0'),
        'method' => $mapped['method'],
        'orderNum' => $withdrawal->order_id,

        // Docs JayaPay pakai "money", bukan "payMoney"
        'money' => (string) $money,

        // 0 = fee dipotong dari nominal payout
        // 1 = fee tidak termasuk / fee dibebankan terpisah
        'feeType' => '0',

        // Docs JayaPay minta kode bank angka
        'bankCode' => $mapped['bank_code'],

        // Docs JayaPay pakai "number", bukan accountNo
        'number' => (string) $account->account_number,

        // Nama penerima
        'name' => (string) $account->account_name,

        // Docs JayaPay pakai "mobile", bukan phone
        'mobile' => $user->phone ?: '081234567890',

        'email' => $user->email ?? 'customer@email.com',
        'notifyUrl' => config('services.jayapay.withdraw_notify_url'),

        // Docs bilang yyyyMMddHHmmss, walau contoh ada format beda.
        // Kita pakai format yang sama seperti deposit.
        'dateTime' => now()->format('YmdHis'),

        // Docs pakai description, bukan productDetail
        'description' => 'Withdraw saldo #' . $withdrawal->order_id,
    ];
}

private function mapPayoutMethod(string $provider): array
{
    $provider = strtoupper(trim($provider));

    $banks = [
        'BCA',
        'BRI',
        'BNI',
        'MANDIRI',
        'PERMATA',
        'CIMB',
        'BSI',
        'BCA_DIGITAL',
        'BCA_SYR',
        'BTN',
        'DANAMON',
        'DBS',
        'MAYBANK',
        'PANIN',
        'OCBC',
        'UOB',
    ];

    $ewallets = [
        'DANA',
        'OVO',
        'GOPAY',
        'SHOPEEPAY',
        'LINKAJA',
    ];

    if (in_array($provider, $banks, true)) {
        return [
            'method' => 'Transfer',
            'bank_code' => $provider,
        ];
    }

    if (in_array($provider, $ewallets, true)) {
        return [
            'method' => $provider,
            'bank_code' => $provider,
        ];
    }

    throw new RuntimeException('Provider payout belum didukung: ' . $provider);
}
    private function makeSign(array $payload): string
{
    unset($payload['sign'], $payload['platSign']);

    $plainText = $this->buildSignPlainText($payload);

    Log::info('JayaPay withdraw sign plain text', [
        'plain_text' => $plainText,
    ]);

    return $this->privateEncrypt($plainText, (string) config('services.jayapay.private_key'));
}

private function buildSignPlainText(array $params): string
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

private function privateEncrypt(string $plainText, string $privateKey): string
{
    $pem = $this->normalizePrivateKey($privateKey);

    $key = openssl_pkey_get_private($pem);

    if (!$key) {
        throw new RuntimeException('Private key JayaPay tidak valid.');
    }

    $details = openssl_pkey_get_details($key);
    $keySize = (int) (($details['bits'] ?? 1024) / 8);
    $maxBlock = $keySize - 11;

    $output = '';

    foreach (str_split($plainText, $maxBlock) as $chunk) {
        $encrypted = '';

        $ok = openssl_private_encrypt($chunk, $encrypted, $key, OPENSSL_PKCS1_PADDING);

        if (!$ok) {
            throw new RuntimeException('Gagal membuat signature JayaPay withdraw.');
        }

        $output .= $encrypted;
    }

    return base64_encode($output);
}
private function normalizePrivateKey(?string $key): string
{
    $key = trim((string) $key);

    if ($key === '') {
        throw new RuntimeException('Private key JayaPay belum diset.');
    }

    if (str_contains($key, 'BEGIN')) {
        return str_replace('\\n', "\n", $key);
    }

    $key = str_replace(["\r", "\n", ' '], '', $key);

    return "-----BEGIN PRIVATE KEY-----\n"
        . chunk_split($key, 64, "\n")
        . "-----END PRIVATE KEY-----\n";
}

private function maskPayloadForLog(array $payload): array
{
    if (isset($payload['number'])) {
        $payload['number'] = $this->mask($payload['number']);
    }

    if (isset($payload['mobile'])) {
        $payload['mobile'] = $this->mask($payload['mobile']);
    }

    if (isset($payload['sign'])) {
        $payload['sign'] = '[signature]';
    }

    return $payload;
}
    private function mask(string $value): string
    {
        $value = (string) $value;

        if (strlen($value) <= 6) {
            return '***';
        }

        return substr($value, 0, 3) . str_repeat('*', max(strlen($value) - 6, 3)) . substr($value, -3);
    }
}