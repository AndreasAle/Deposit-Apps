<?php

namespace App\Http\Controllers;

use App\Models\Deposit;
use App\Models\Withdrawal;
use App\Services\BankPay\BankPayDepositService;
use App\Services\BankPay\BankPayPayoutService;
use App\Services\DepositChannels;
use App\Services\DepositSettlementService;
use App\Services\WithdrawalSettlementService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * Notifikasi server (webhook) dari payment gateway BankPay.
 *
 * Aturan protokolnya:
 *   - Notifikasi dikirim sebagai HTTP POST FORM, bukan JSON.
 *   - Penerima WAJIB membalas persis string "OK" sebagai tanda terima.
 *     Balasan lain membuat gateway mengulang notifikasi sampai tiga kali —
 *     itu sengaja dimanfaatkan: kalau pemrosesan gagal, kita balas selain "OK"
 *     supaya dicoba lagi. Aman karena penyelesaiannya idempoten.
 *   - Nominal yang dicatat harus nominal riil dari notifikasi, bukan nominal
 *     yang diminta user.
 */
class BankPayCallbackController extends Controller
{
    /**
     * Notifikasi hasil pembayaran (deposit).
     *
     * Field: memId, orderNo, transId, payAmount, datetime, code, msg, attach, sign
     */
    public function deposit(
        Request $request,
        BankPayDepositService $bankPay,
        DepositSettlementService $settlement
    ) {
        $params = $request->all();

        Log::info('BankPay deposit notify diterima', ['params' => $params]);

        if (!$bankPay->verifyNotification($params)) {
            Log::warning('BankPay deposit notify: tanda tangan tidak sah', ['params' => $params]);

            return $this->reject('invalid signature');
        }

        if (!$this->belongsToUs($params['memId'] ?? null, $bankPay->memberId())) {
            Log::warning('BankPay deposit notify: memId bukan milik kita', ['params' => $params]);

            return $this->reject('unknown merchant');
        }

        $orderId = trim((string) ($params['orderNo'] ?? ''));

        if ($orderId === '') {
            return $this->reject('orderNo kosong');
        }

        $deposit = Deposit::where('order_id', $orderId)->first();

        if (!$deposit) {
            Log::warning('BankPay deposit notify: order tidak ditemukan', ['order_id' => $orderId]);

            // Order tidak dikenal tidak akan pernah jadi dikenal — balas OK
            // supaya gateway berhenti mengulang.
            return $this->accept();
        }

        // Deposit QRIS statis tidak pernah dikirim ke gateway. Kalau ada
        // notifikasi untuk order seperti itu, itu tanda ada yang salah —
        // jangan sampai bisa dipakai melunasi invoice jalur lain.
        if ($deposit->payment_channel !== DepositChannels::BANKPAY) {
            Log::warning('BankPay deposit notify: saluran deposit tidak cocok', [
                'order_id' => $orderId,
                'channel' => $deposit->payment_channel,
            ]);

            return $this->accept();
        }

        // "1" satu-satunya penanda sukses; sisanya kegagalan.
        if ((string) ($params['code'] ?? '') !== '1') {
            Log::info('BankPay deposit notify: pembayaran tidak sukses', [
                'order_id' => $orderId,
                'code' => $params['code'] ?? null,
                'msg' => $params['msg'] ?? null,
            ]);

            return $this->accept();
        }

        try {
            $settlement->settle(
                $deposit->id,
                $bankPay->parseMoney($params['payAmount'] ?? 0),
                trim((string) ($params['transId'] ?? '')) ?: null,
                $params,
                'notify'
            );
        } catch (\Throwable $e) {
            Log::error('BankPay deposit notify: gagal melunasi', [
                'order_id' => $orderId,
                'message' => $e->getMessage(),
            ]);

            // Bukan "OK" -> gateway mengulang, dan kita coba lagi.
            return $this->reject('settlement error');
        }

        return $this->accept();
    }

    /**
     * Notifikasi hasil pembayaran atas nama (withdraw).
     *
     * Field: memberid, orderid, transaction_id, amount, datetime, returncode, msg, sign
     */
    public function payout(
        Request $request,
        BankPayPayoutService $bankPay,
        WithdrawalSettlementService $settlement
    ) {
        $params = $request->all();

        Log::info('BankPay payout notify diterima', ['params' => $params]);

        if (!$bankPay->verifyNotification($params)) {
            Log::warning('BankPay payout notify: tanda tangan tidak sah', ['params' => $params]);

            return $this->reject('invalid signature');
        }

        if (!$this->belongsToUs($params['memberid'] ?? null, $bankPay->memberId())) {
            Log::warning('BankPay payout notify: memberid bukan milik kita', ['params' => $params]);

            return $this->reject('unknown merchant');
        }

        $orderId = trim((string) ($params['orderid'] ?? ''));

        if ($orderId === '') {
            return $this->reject('orderid kosong');
        }

        $withdrawal = Withdrawal::where('order_id', $orderId)->first();

        if (!$withdrawal) {
            Log::warning('BankPay payout notify: withdrawal tidak ditemukan', ['order_id' => $orderId]);

            return $this->accept();
        }

        $returnCode = strtolower(trim((string) ($params['returncode'] ?? '')));

        try {
            if ($returnCode === '00') {
                $settlement->markPaid($withdrawal->id, $params, 'notify');
            } else {
                $settlement->markFailed(
                    $withdrawal->id,
                    trim((string) ($params['msg'] ?? '')) ?: 'Penarikan ditolak gateway',
                    $params,
                    'notify'
                );
            }
        } catch (\Throwable $e) {
            Log::error('BankPay payout notify: gagal memproses', [
                'order_id' => $orderId,
                'message' => $e->getMessage(),
            ]);

            return $this->reject('settlement error');
        }

        return $this->accept();
    }

    /**
     * Pastikan notifikasi memang ditujukan ke akun merchant kita. Tanda tangan
     * sudah menjamin keasliannya, ini lapisan kedua terhadap salah konfigurasi.
     */
    private function belongsToUs(mixed $incoming, string $ours): bool
    {
        $incoming = trim((string) $incoming);

        return $incoming === '' || $ours === '' || $incoming === $ours;
    }

    /** Tanda terima yang diharapkan gateway. */
    private function accept()
    {
        return response('OK')->header('Content-Type', 'text/plain');
    }

    /** Balasan selain "OK" -> gateway akan mengulang notifikasi. */
    private function reject(string $reason)
    {
        return response('FAIL: ' . $reason, 400)->header('Content-Type', 'text/plain');
    }
}
