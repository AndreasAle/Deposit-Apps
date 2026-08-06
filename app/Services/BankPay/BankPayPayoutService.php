<?php

namespace App\Services\BankPay;

use App\Models\Withdrawal;
use RuntimeException;

/**
 * Sisi PAYOUT (withdraw) dari payment gateway BankPay — "pembayaran atas nama".
 *
 * Catatan penting dari dokumentasi:
 *   - Antarmuka ini dibatasi 5 permintaan per detik.
 *   - Nomor pesanan wajib unik, 16-32 karakter.
 *   - Semua parameter tidak kosong selain `sign` ikut ditandatangani.
 */
class BankPayPayoutService extends BankPayClient
{
    /**
     * Kirim permintaan pencairan ke BankPay.
     *
     * @return array{transaction_id:?string, accepted:bool, response:array<string,mixed>}
     *
     * @throws RuntimeException kalau gateway menolak permintaan
     */
    public function createPayout(Withdrawal $withdrawal): array
    {
        $withdrawal->loadMissing(['user', 'payoutAccount']);

        $account = $withdrawal->payoutAccount;

        if (!$account) {
            throw new RuntimeException('Akun penarikan tidak ditemukan.');
        }

        $bank = BankPayBanks::find($account->provider);

        if (!$bank) {
            throw new RuntimeException(
                'Tujuan penarikan "' . BankPayBanks::normalize($account->provider) . '" tidak didukung gateway.'
            );
        }

        // Yang dicairkan adalah nominal bersih setelah biaya admin kita.
        $net = (float) $withdrawal->net_amount;

        if ($net <= 0) {
            throw new RuntimeException('Nominal penarikan tidak valid.');
        }

        $user = $withdrawal->user;

        $params = [
            'memberid' => $this->memberId,
            'orderid' => $withdrawal->order_id,
            'bankcode' => $this->bankCode,
            'notifyurl' => $this->payoutNotifyUrl(),
            'amount' => $this->money($net),
            'mobile' => $this->contactMobile($user),
            'email' => $this->contactEmail($user),
            'pay_currency' => $this->currency,

            // Jalur kartu bank / rekening (bukan UPI). `vpa` sengaja tidak
            // dikirim — kalau dikirim, gateway otomatis memakai jalur UPI.
            'bankname' => $bank['name'],
            'bankno' => $bank['code'],
            'cardnumber' => preg_replace('/\s+/', '', (string) $account->account_number),
            'accountname' => trim((string) $account->account_name),
        ];

        $params['sign'] = $this->sign($params);

        $result = $this->post('Pay-payment-draw.aspx', $params);
        $body = $result['body'];

        // Sukses: {"status":1,"msg":{...}}. Gagal: {"status":0,"msg":"Error ..."}.
        $status = (string) ($body['status'] ?? '');
        $detail = is_array($body['msg'] ?? null) ? $body['msg'] : [];

        if (!$result['ok'] || $status !== '1' || $detail === []) {
            throw new RuntimeException(
                $this->errorMessage($body, 'Gateway menolak permintaan penarikan (HTTP ' . $result['http'] . ').')
            );
        }

        $tradeState = strtoupper((string) ($detail['trade_state'] ?? ''));

        if ($tradeState !== 'SUCCESS') {
            throw new RuntimeException('Permintaan penarikan ditolak gateway (' . ($tradeState ?: 'tanpa status') . ').');
        }

        return [
            // "SUCCESS" di sini berarti DITERIMA untuk diproses, bukan dana
            // sudah cair. Status akhir datang lewat notifyurl / dfquery.
            'accepted' => true,
            'transaction_id' => $this->stringOrNull($detail['transaction_id'] ?? null),
            'response' => $body,
        ];
    }

    /**
     * Tanya status pencairan ke BankPay (cadangan kalau notifikasi tak sampai).
     *
     * @return array{success:bool, state:?string, amount:?float, fee:?float, message:?string, response:array<string,mixed>}
     */
    public function queryPayout(string $orderId): array
    {
        $blank = [
            'success' => false, 'state' => null, 'amount' => null,
            'fee' => null, 'message' => null, 'response' => [],
        ];

        if (!$this->isConfigured()) {
            return $blank;
        }

        $params = [
            'memberid' => $this->memberId,
            'orderid' => $orderId,
        ];

        $params['sign'] = $this->sign($params);

        try {
            $result = $this->post('Pay-Trade-dfquery.aspx', $params, 20);
        } catch (\Throwable) {
            return $blank;
        }

        $body = $result['body'];

        if (!$result['ok'] || (string) ($body['returncode'] ?? '') !== '00') {
            return array_merge($blank, ['response' => $body]);
        }

        return [
            'success' => true,
            // WAIT PAY = diproses, SUCCESS = cair, REFUSE = ditolak.
            'state' => strtoupper(trim((string) ($body['trade_state'] ?? ''))),
            'amount' => isset($body['amount']) ? $this->parseMoney($body['amount']) : null,
            'fee' => isset($body['fee']) ? $this->parseMoney($body['fee']) : null,
            'message' => $this->stringOrNull($body['trade_msg'] ?? null),
            'response' => $body,
        ];
    }

    /**
     * Saldo merchant di BankPay. Dipakai halaman admin untuk memastikan
     * dana pencairan mencukupi sebelum memproses antrian withdraw.
     *
     * @return array{success:bool, balance:?float, response:array<string,mixed>}
     */
    public function getBalance(): array
    {
        if (!$this->isConfigured()) {
            return ['success' => false, 'balance' => null, 'response' => []];
        }

        // Kekhususan endpoint ini: `currency` TIDAK ikut ditandatangani.
        $params = ['memberid' => $this->memberId];
        $params['sign'] = $this->sign($params);
        $params['currency'] = $this->currency;

        try {
            $result = $this->post('Pay-Trade-getBalance.aspx', $params, 20);
        } catch (\Throwable) {
            return ['success' => false, 'balance' => null, 'response' => []];
        }

        $body = $result['body'];

        if (!$result['ok'] || (string) ($body['returncode'] ?? '') !== '00') {
            return ['success' => false, 'balance' => null, 'response' => $body];
        }

        return [
            'success' => true,
            'balance' => isset($body['balance']) ? $this->parseMoney($body['balance']) : null,
            'response' => $body,
        ];
    }

    /**
     * Gateway mewajibkan nomor ponsel penerima. Kalau profil user kosong,
     * kirim placeholder agar permintaan tidak ditolak karena field kosong.
     */
    private function contactMobile(mixed $user): string
    {
        $phone = preg_replace('/\D/', '', (string) ($user->phone ?? ''));

        return $phone !== '' ? $phone : '0000000000';
    }

    private function contactEmail(mixed $user): string
    {
        $email = trim((string) ($user->email ?? ''));

        return $email !== '' ? $email : ('user' . ($user->id ?? 0) . '@capitalwave.local');
    }

    private function stringOrNull(mixed $value): ?string
    {
        if ($value === null) {
            return null;
        }

        $value = trim((string) $value);

        return $value === '' ? null : $value;
    }
}
