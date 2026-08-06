<?php

namespace App\Http\Controllers;

use App\Models\Withdrawal;
use App\Services\BankPay\BankPayBanks;
use App\Services\BankPay\BankPayPayoutService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * Penarikan saldo lewat payment gateway BankPay ("pembayaran atas nama").
 *
 * Hanya saluran gateway yang bisa melakukan penarikan — QRIS statis sifatnya
 * terima uang saja, tidak bisa mengirim.
 *
 * Alur saldo: saldo_penarikan -> saldo_hold saat request, lalu hold dilepas
 * kalau cair atau dikembalikan kalau gagal (lihat WithdrawalSettlementService).
 */
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

    public function store(Request $request, BankPayPayoutService $bankPay)
    {
        $data = $request->validate([
            'amount' => ['required', 'integer', 'min:' . self::MIN_WITHDRAW, 'max:' . self::MAX_WITHDRAW],
            'user_payout_account_id' => ['required', 'integer'],
        ]);

        $user = $request->user();

        $account = $user->payoutAccounts()
            ->where('id', $data['user_payout_account_id'])
            ->firstOrFail();

        if (!BankPayBanks::supports($account->provider)) {
            return response()->json([
                'message' => 'Tujuan penarikan "' . BankPayBanks::normalize($account->provider)
                    . '" tidak didukung. Gunakan bank atau e-wallet yang tersedia.',
            ], 422);
        }

        $amount = (int) $data['amount'];

        $fee = (int) round($amount * self::WITHDRAW_FEE_PERCENT / 100);
        $net = $amount - $fee;

        /*
         * Step 1:
         * Buat record withdraw dan tahan saldo penarikan.
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

                // Wajib unik dan 16-32 karakter (ketentuan gateway). Ini 22 karakter.
                'order_id' => 'WD' . now()->format('YmdHis') . strtoupper(Str::random(6)),
                'method' => null,
                'bank_code' => BankPayBanks::normalize($account->provider),
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
         * Kirim permintaan pencairan ke gateway. Kalau gagal terkirim, saldo
         * penarikan langsung dikembalikan supaya dana user tidak menggantung.
         *
         * Pencocokan notifikasi memakai `order_id` kita sendiri (BankPay
         * mengembalikannya sebagai `orderid`), jadi tidak bergantung pada
         * nomor transaksi gateway.
         */
        try {
            $result = $bankPay->createPayout($withdrawal->fresh(['user', 'payoutAccount']));

            $withdrawal->update([
                // Diterima gateway, belum tentu cair. Status akhir datang dari
                // notifikasi server atau poller.
                'status' => 'PROCESSING',
                'plat_order_num' => $result['transaction_id'],
                'gateway_status' => 'WAIT PAY',
                'gateway_message' => 'Diterima gateway',
                'gateway_response' => $result['response'],
                'processing_at' => now(),
            ]);

            return response()->json([
                'message' => 'Withdraw berhasil dibuat dan sedang diproses gateway.',
                'data' => $withdrawal->fresh('payoutAccount'),
            ], 201);
        } catch (\Throwable $e) {
            report($e);

            DB::transaction(function () use ($withdrawal, $e) {
                $row = Withdrawal::where('id', $withdrawal->id)
                    ->lockForUpdate()
                    ->firstOrFail();

                // Hanya PENDING yang boleh di-refund di sini: kalau statusnya
                // sudah bergerak, jalur lain yang memegang saldonya.
                if ($row->status !== 'PENDING') {
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
}
