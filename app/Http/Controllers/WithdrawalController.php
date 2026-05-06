<?php

namespace App\Http\Controllers;

use App\Models\Withdrawal;
use App\Services\JayaPayWithdrawService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class WithdrawalController extends Controller
{
    private const MIN_WITHDRAW = 50000;
    private const MAX_WITHDRAW = 50000000;
    private const WITHDRAW_FEE_PERCENT = 0.10;

    public function index(Request $request)
    {
        $rows = $request->user()->withdrawals()
            ->with('payoutAccount')
            ->latest()
            ->paginate(20);

        return response()->json($rows);
    }

    public function show(Request $request, $id)
    {
        $row = $request->user()->withdrawals()
            ->with('payoutAccount')
            ->where('id', $id)
            ->firstOrFail();

        return response()->json(['data' => $row]);
    }

    public function store(Request $request, JayaPayWithdrawService $jayaPay)
    {
        $data = $request->validate([
            'amount' => ['required', 'integer', 'min:' . self::MIN_WITHDRAW, 'max:' . self::MAX_WITHDRAW],
            'user_payout_account_id' => ['required', 'integer'],
        ]);

        $user = $request->user();

        $account = $user->payoutAccounts()
            ->where('id', $data['user_payout_account_id'])
            ->firstOrFail();

        $amount = (int) $data['amount'];
        $fee = (int) floor($amount * self::WITHDRAW_FEE_PERCENT);
        $net = $amount - $fee;

        /*
         * Step 1:
         * Buat record withdraw dan hold saldo penarikan.
         */
        $withdrawal = DB::transaction(function () use ($user, $account, $amount, $fee, $net) {
            $lockedUser = $user->newQuery()
                ->where('id', $user->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ((float) $lockedUser->saldo_penarikan < $amount) {
                abort(422, 'Saldo penarikan tidak cukup untuk withdraw.');
            }

            $lockedUser->saldo_penarikan = (float) $lockedUser->saldo_penarikan - $amount;
            $lockedUser->saldo_hold = (float) $lockedUser->saldo_hold + $amount;
            $lockedUser->save();

            return Withdrawal::create([
                'user_id' => $lockedUser->id,
                'user_payout_account_id' => $account->id,

                'order_id' => 'WD' . now()->format('YmdHis') . strtoupper(Str::random(6)),
                'method' => null,
                'bank_code' => strtoupper((string) $account->provider),
                'account_no' => (string) $account->account_number,
                'account_name' => (string) $account->account_name,

                'amount' => $amount,
                'fee' => $fee,
                'net_amount' => $net,

                'status' => 'PENDING',
                'requested_at' => now(),
            ]);
        });

        /*
         * Step 2:
         * Kirim payout ke JayaPay.
         * Kalau gagal, saldo penarikan otomatis dikembalikan.
         */
        try {
            $response = $jayaPay->createPayout($withdrawal->fresh(['user', 'payoutAccount']));

            $platOrderNum = data_get($response, 'platOrderNum')
                ?: data_get($response, 'data.platOrderNum')
                ?: data_get($response, 'platOrderNo')
                ?: null;

            $respCode = data_get($response, 'platRespCode')
                ?: data_get($response, 'code')
                ?: data_get($response, 'status')
                ?: null;

            $respMessage = data_get($response, 'platRespMessage')
                ?: data_get($response, 'msg')
                ?: data_get($response, 'message')
                ?: null;

            $isAccepted = $this->isJayaPayPayoutAccepted($response);

            if (!$isAccepted) {
                throw new \RuntimeException($respMessage ?: 'Payout JayaPay belum diterima.');
            }

            $withdrawal->update([
                'status' => 'PROCESSING',
                'plat_order_num' => $platOrderNum,
                'gateway_status' => (string) $respCode,
                'gateway_message' => $respMessage,
                'gateway_response' => $response,
                'processing_at' => now(),
            ]);

            return response()->json([
                'message' => 'Withdraw berhasil dibuat dan sedang diproses gateway.',
                'data' => $withdrawal->fresh('payoutAccount'),
            ], 201);
        } catch (\Throwable $e) {
            report($e);

            /*
             * Kalau gagal kirim ke JayaPay, refund saldo penarikan.
             */
            DB::transaction(function () use ($withdrawal, $e) {
                $row = Withdrawal::where('id', $withdrawal->id)
                    ->lockForUpdate()
                    ->firstOrFail();

                if (!in_array($row->status, ['PENDING'], true)) {
                    return;
                }

                $user = $row->user()->lockForUpdate()->firstOrFail();

                $user->saldo_penarikan = (float) $user->saldo_penarikan + (float) $row->amount;
                $user->saldo_hold = max(0, (float) $user->saldo_hold - (float) $row->amount);
                $user->save();

                $row->update([
                    'status' => 'FAILED',
                    'gateway_status' => 'REQUEST_FAILED',
                    'gateway_message' => $e->getMessage(),
                    'failed_reason' => $e->getMessage(),
                    'failed_at' => now(),
                ]);
            });

            return response()->json([
                'message' => 'Withdraw gagal dikirim ke gateway: ' . $e->getMessage(),
            ], 422);
        }
    }

    public function cancel(Request $request, $id)
    {
        $user = $request->user();

        $withdrawal = $user->withdrawals()
            ->where('id', $id)
            ->firstOrFail();

        if ($withdrawal->status !== 'PENDING') {
            return response()->json([
                'message' => 'Hanya request PENDING yang bisa dibatalkan.',
            ], 422);
        }

        DB::transaction(function () use ($user, $withdrawal) {
            $row = Withdrawal::where('id', $withdrawal->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($row->status !== 'PENDING') {
                abort(422, 'Status withdraw sudah berubah.');
            }

            $lockedUser = $user->newQuery()
                ->where('id', $user->id)
                ->lockForUpdate()
                ->firstOrFail();

            $lockedUser->saldo_penarikan = (float) $lockedUser->saldo_penarikan + (float) $row->amount;
            $lockedUser->saldo_hold = max(0, (float) $lockedUser->saldo_hold - (float) $row->amount);
            $lockedUser->save();

            $row->update([
                'status' => 'CANCELLED',
            ]);
        });

        return response()->json(['message' => 'Withdraw request dibatalkan']);
    }

    private function isJayaPayPayoutAccepted(array $response): bool
    {
        $platRespCode = strtoupper((string) data_get($response, 'platRespCode'));
        $status = (string) data_get($response, 'status');

        return $platRespCode === 'SUCCESS'
            || in_array($status, ['0', '1', '2', '5'], true);
    }

    public function jayaPayCallback(Request $request)
    {
        $payload = $request->all();

        $orderNum = data_get($payload, 'orderNum');
        $platOrderNum = data_get($payload, 'platOrderNum');
        $status = strtoupper((string) data_get($payload, 'status'));
        $code = (string) data_get($payload, 'code');
        $msg = data_get($payload, 'msg') ?: data_get($payload, 'message');

        if (!$orderNum && !$platOrderNum) {
            return response('missing order', 400);
        }

        DB::transaction(function () use ($payload, $orderNum, $platOrderNum, $status, $code, $msg) {
            $query = Withdrawal::query()->lockForUpdate();

            if ($orderNum) {
                $query->where('order_id', $orderNum);
            } else {
                $query->where('plat_order_num', $platOrderNum);
            }

            $withdrawal = $query->firstOrFail();

            if ($withdrawal->status === 'PAID') {
                return;
            }

            if ($platOrderNum && empty($withdrawal->plat_order_num)) {
                $withdrawal->plat_order_num = $platOrderNum;
            }

            $withdrawal->gateway_status = $status ?: $code;
            $withdrawal->gateway_message = $msg;
            $withdrawal->gateway_callback = $payload;

            if ($this->isPayoutCallbackSuccess($status, $code)) {
                $user = $withdrawal->user()->lockForUpdate()->firstOrFail();

                $user->saldo_hold = max(0, (float) $user->saldo_hold - (float) $withdrawal->amount);
                $user->save();

                $withdrawal->status = 'PAID';
                $withdrawal->paid_at = now();
                $withdrawal->save();

                return;
            }

            if ($this->isPayoutCallbackFailed($status, $code)) {
                $user = $withdrawal->user()->lockForUpdate()->firstOrFail();

                $user->saldo_penarikan = (float) $user->saldo_penarikan + (float) $withdrawal->amount;
                $user->saldo_hold = max(0, (float) $user->saldo_hold - (float) $withdrawal->amount);
                $user->save();

                $withdrawal->status = 'FAILED';
                $withdrawal->failed_reason = $msg ?: 'Payout failed from gateway';
                $withdrawal->failed_at = now();
                $withdrawal->save();

                return;
            }

            $withdrawal->status = 'PROCESSING';
            $withdrawal->save();
        });

        /*
         * JayaPay biasanya minta response string SUCCESS.
         */
        return response('SUCCESS', 200);
    }

    private function isPayoutCallbackSuccess(string $status, string $code): bool
    {
        return $status === '2';
    }

    private function isPayoutCallbackFailed(string $status, string $code): bool
    {
        return $status === '4';
    }
}