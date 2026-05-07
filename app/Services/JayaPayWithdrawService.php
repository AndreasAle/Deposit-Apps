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
            $body = ['raw' => $response->body()];
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
            'money' => (string) $money,
            'feeType' => '0',
            'bankCode' => $mapped['bank_code'],
            'number' => $this->normalizeAccountNumber($provider, (string) $account->account_number),
            'name' => trim((string) $account->account_name),
            'mobile' => $this->normalizePhone($user->phone ?: '081234567890'),
            'email' => $user->email ?? 'customer@email.com',
            'notifyUrl' => config('services.jayapay.withdraw_notify_url'),
            'dateTime' => now()->format('YmdHis'),
            'description' => 'Withdraw saldo #' . $withdrawal->order_id,
        ];
    }

    private function mapPayoutMethod(string $provider): array
    {
        $provider = strtoupper(trim($provider));

        $bankCodes = [
            // Major banks
            'BCA' => '014',
            'BRI' => '002',
            'BNI' => '009',
            'MANDIRI' => '008',
            'PERMATA' => '013',
            'CIMB' => '022',
            'BSI' => '4510',
            'BTN' => '200',
            'DANAMON' => '011',
            'DBS' => '046',
            'MAYBANK' => '016',
            'PANIN' => '019',
            'OCBC' => '028',
            'UOB' => '023',

            // Syariah / variants
            'BCA_SYR' => '536',
            'BRI_SYR' => '422',
            'BNI_SYR' => '427',
            'MANDIRI_SYR' => '451',
            'PERMATA_UUS' => '0130',
            'CIMB_UUS' => '0220',
            'BTN_UUS' => '2000',
            'DANAMON_UUS' => '0110',
            'MAYBANK_SYR' => '947',
            'OCBC_UUS' => '0280',

            // Common digital / other banks
            'JAGO' => '542',
            'ARTOS' => '542',
            'SEABANK' => '535',
            'BKE' => '535',
            'NEO' => '490',
            'BNC' => '490',
            'ALLO' => '567',
            'HANA' => '484',
            'LINE' => '484',
            'MEGA' => '426',
            'SINARMAS' => '153',
            'MUAMALAT' => '147',
            'BTPN' => '213',
            'BUKOPIN' => '441',
            'JTRUST' => '095',
            'MAYAPADA' => '097',
            'NOBU' => '503',
            'NATIONALNOBU' => '503',
            'QNB' => '1670',
            'OKE' => '5260',
            'SHINHAN' => '152',
            'WOORI' => '212',

            // E-wallets
            'OVO' => '10001',
            'DANA' => '10002',
            'GOPAY' => '10003',
            'SHOPEEPAY' => '10008',
            'LINKAJA' => '10009',
        ];

        $ewallets = [
            'OVO',
            'DANA',
            'GOPAY',
            'SHOPEEPAY',
            'LINKAJA',
        ];

        if (!isset($bankCodes[$provider])) {
            throw new RuntimeException('Provider payout belum didukung / belum ada kode bank JayaPay: ' . $provider);
        }

        return [
            'method' => in_array($provider, $ewallets, true) ? $provider : 'Transfer',
            'bank_code' => $bankCodes[$provider],
        ];
    }

    private function normalizeAccountNumber(string $provider, string $number): string
    {
        $number = preg_replace('/\D+/', '', $number) ?: '';

        $ewallets = ['OVO', 'DANA', 'GOPAY', 'SHOPEEPAY', 'LINKAJA'];

        if (in_array(strtoupper($provider), $ewallets, true)) {
            if (str_starts_with($number, '62')) {
                return $number;
            }

            if (str_starts_with($number, '0')) {
                return '62' . substr($number, 1);
            }
        }

        return $number;
    }

    private function normalizePhone(string $phone): string
    {
        $phone = preg_replace('/\D+/', '', $phone) ?: '';

        if (str_starts_with($phone, '62')) {
            return '0' . substr($phone, 2);
        }

        return $phone;
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
            if ($value === null || $value === '') {
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
            $payload['number'] = $this->mask((string) $payload['number']);
        }

        if (isset($payload['mobile'])) {
            $payload['mobile'] = $this->mask((string) $payload['mobile']);
        }

        if (isset($payload['sign'])) {
            $payload['sign'] = '[signature]';
        }

        return $payload;
    }

    private function mask(string $value): string
    {
        if (strlen($value) <= 6) {
            return '***';
        }

        return substr($value, 0, 3)
            . str_repeat('*', max(strlen($value) - 6, 3))
            . substr($value, -3);
    }
}