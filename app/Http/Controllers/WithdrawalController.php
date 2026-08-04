<?php

namespace App\Http\Controllers;

use App\Models\Withdrawal;
use App\Services\BayarProPayoutService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class WithdrawalController extends Controller
{
    private const MIN_WITHDRAW = 50000;
    private const MAX_WITHDRAW = 50000000;
    private const WITHDRAW_FEE_PERCENT = 5;

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

    public function store(Request $request, BayarProPayoutService $bayarPro)
    {
        $data = $request->validate([
            'amount' => ['required', 'integer', 'min:' . self::MIN_WITHDRAW, 'max:' . self::MAX_WITHDRAW],
            'user_payout_account_id' => ['required', 'integer'],
        ]);

        $user = $request->user();

        $account = $user->payoutAccounts()
            ->where('id', $data['user_payout_account_id'])
            ->firstOrFail();

        $provider = strtoupper(trim((string) $account->provider));

        if (!isset(BayarProPayoutService::BANK_TARGETS[$provider])) {
            return response()->json([
                'message' => 'Tujuan penarikan "' . $provider . '" tidak didukung. Gunakan bank atau DANA/OVO.',
            ], 422);
        }

        $amount = (int) $data['amount'];

        $fee = (int) round($amount * self::WITHDRAW_FEE_PERCENT / 100);
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
         * Kirim payout ke BayarPro.
         * Kalau gagal, saldo penarikan otomatis dikembalikan.
         *
         * Catatan: BayarPro tidak menerima merchant_ref_id pada payout, jadi
         * pencocokan webhook memakai payout_id yang disimpan di plat_order_num.
         */
        try {
            $response = $bayarPro->createPayout($withdrawal->fresh(['user', 'payoutAccount']));

            $payoutId = data_get($response, 'data.payout_id')
                ?: data_get($response, 'data.reference_id')
                ?: data_get($response, 'data.id')
                ?: data_get($response, 'data.payoutId')
                ?: data_get($response, 'data.payout_ref_id')
                ?: data_get($response, 'payout_id')
                ?: data_get($response, 'reference_id')
                ?: data_get($response, 'id')
                ?: null;

            $respMessage = data_get($response, 'message') ?: 'OK';

            if (!$payoutId) {
                throw new \RuntimeException('Payout BayarPro tidak mengembalikan payout_id.');
            }

            $withdrawal->update([
                'status' => 'PROCESSING',
                'plat_order_num' => $payoutId,
                'gateway_status' => (string) (data_get($response, 'data.status') ?: 'PENDING'),
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

    /**
     * Webhook payout dari BayarPro.
     * Verifikasi HMAC SHA256 dari raw body, cocokkan withdrawal via payout_id
     * (disimpan di plat_order_num) karena payout tidak punya merchant_ref_id.
     */
    public function bayarProPayoutCallback(Request $request, BayarProPayoutService $bayarPro)
    {
        $rawBody = $request->getContent();
        $signature = (string) $request->header('X-Bayarpro-Signature', '');

        if (!$bayarPro->verifyWebhookSignature($rawBody, $signature)) {
            Log::warning('BayarPro payout callback invalid signature', ['body' => $rawBody]);
            return response('Invalid Webhook Signature', 401);
        }

        $payload = json_decode($rawBody, true) ?: [];

        Log::info('BayarPro payout callback received', $payload);

        $event = $payload['event'] ?? null;
        $data = $payload['data'] ?? [];
        $payoutId = $data['payout_id'] ?? null;
        $status = strtoupper((string) ($data['status'] ?? ''));

        if (!$payoutId) {
            return response('payout_id empty', 400);
        }

        DB::transaction(function () use ($payload, $payoutId, $status, $event) {
            $withdrawal = Withdrawal::query()
                ->where('plat_order_num', $payoutId)
                ->lockForUpdate()
                ->first();

            if (!$withdrawal) {
                Log::warning('BayarPro payout callback withdrawal not found', $payload);
                return;
            }

            if (in_array($withdrawal->status, ['PAID', 'FAILED'], true)) {
                return;
            }

            $withdrawal->gateway_status = $status;
            $withdrawal->gateway_message = $payload['event'] ?? null;
            $withdrawal->gateway_callback = $payload;

            if ($event === 'payout.success' && $status === 'SUCCESS') {
                $user = $withdrawal->user()->lockForUpdate()->firstOrFail();

                // Saldo sudah di-hold saat request; sukses = lepas hold (dana keluar).
                $user->saldo_hold = max(0, (float) $user->saldo_hold - (float) $withdrawal->amount);
                $user->save();

                $withdrawal->status = 'PAID';
                $withdrawal->paid_at = now();
                $withdrawal->save();

                return;
            }

            if ($event === 'payout.failed' || $status === 'FAILED') {
                $user = $withdrawal->user()->lockForUpdate()->firstOrFail();

                // Gagal = kembalikan saldo penarikan dari hold.
                $user->saldo_penarikan = (float) $user->saldo_penarikan + (float) $withdrawal->amount;
                $user->saldo_hold = max(0, (float) $user->saldo_hold - (float) $withdrawal->amount);
                $user->save();

                $withdrawal->status = 'FAILED';
                $withdrawal->failed_reason = 'Payout gagal dari gateway';
                $withdrawal->failed_at = now();
                $withdrawal->save();

                return;
            }

            $withdrawal->status = 'PROCESSING';
            $withdrawal->save();
        });

        return response()->json(['status' => 'ok']);
    }
}