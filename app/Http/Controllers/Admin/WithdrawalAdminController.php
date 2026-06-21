<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Withdrawal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class WithdrawalAdminController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status');

        $q = Withdrawal::query()
            ->with(['user', 'payoutAccount'])
            ->where('is_test', false)
            ->latest();

        if ($status) {
            $q->where('status', $status);
        }

        return response()->json($q->paginate(30));
    }

    /*
    |--------------------------------------------------------------------------
    | WD Testing Tools
    |--------------------------------------------------------------------------
    | Dipakai admin untuk membuat withdrawal dummy (is_test = true) dan
    | mensimulasikan callback PAID/FAILED tanpa hit API JayaPay sungguhan.
    | Tidak menyentuh saldo user nyata karena tujuannya hanya cek alur status & UI.
    */

    public function testLookupUser(Request $request)
    {
        $q = trim((string) $request->query('q', ''));

        if ($q === '') {
            return response()->json(['data' => []]);
        }

        $users = User::query()
            ->where(function ($query) use ($q) {
                $query->where('name', 'like', "%{$q}%")
                    ->orWhere('phone', 'like', "%{$q}%");

                if (ctype_digit($q)) {
                    $query->orWhere('id', $q);
                }
            })
            ->with('payoutAccounts')
            ->limit(10)
            ->get();

        return response()->json(['data' => $users]);
    }

    public function testIndex()
    {
        $rows = Withdrawal::query()
            ->with(['user', 'payoutAccount'])
            ->where('is_test', true)
            ->latest()
            ->paginate(30);

        return response()->json($rows);
    }

    public function testStore(Request $request)
    {
        $data = $request->validate([
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'user_payout_account_id' => ['nullable', 'integer'],
            'provider' => ['nullable', 'string', 'max:50'],
            'account_number' => ['nullable', 'string', 'max:50'],
            'account_name' => ['nullable', 'string', 'max:100'],
            'amount' => ['required', 'integer', 'min:1000', 'max:50000000'],
        ]);

        $accountNo = $data['account_number'] ?? null;
        $accountName = $data['account_name'] ?? null;
        $provider = $data['provider'] ?? null;

        if (!empty($data['user_payout_account_id'])) {
            $account = User::find($data['user_id'])
                ?->payoutAccounts()
                ->where('id', $data['user_payout_account_id'])
                ->first();

            if ($account) {
                $provider = $account->provider;
                $accountNo = $account->account_number;
                $accountName = $account->account_name;
            }
        }

        $withdrawal = Withdrawal::create([
            'user_id' => $data['user_id'],
            'user_payout_account_id' => $data['user_payout_account_id'] ?? null,

            'order_id' => 'TEST' . now()->format('YmdHis') . strtoupper(Str::random(6)),
            'bank_code' => strtoupper((string) ($provider ?: 'TEST')),
            'account_no' => (string) ($accountNo ?: '0000000000'),
            'account_name' => (string) ($accountName ?: 'Test User'),

            'amount' => $data['amount'],
            'fee' => 0,
            'net_amount' => $data['amount'],

            'status' => 'PROCESSING',
            'gateway_status' => 'TEST',
            'gateway_message' => 'Simulated test withdrawal (tidak dikirim ke JayaPay).',
            'requested_at' => now(),
            'processing_at' => now(),
            'is_test' => true,
        ]);

        return response()->json([
            'message' => 'Test withdrawal dibuat.',
            'data' => $withdrawal->fresh(['user', 'payoutAccount']),
        ], 201);
    }

    public function testSimulate(Request $request, $id)
    {
        $data = $request->validate([
            'status' => ['required', 'in:PAID,FAILED,PROCESSING'],
            'reason' => ['nullable', 'string', 'max:500'],
        ]);

        $row = Withdrawal::where('id', $id)
            ->where('is_test', true)
            ->firstOrFail();

        $update = [
            'status' => $data['status'],
            'gateway_status' => 'TEST_' . $data['status'],
            'gateway_message' => $data['status'] === 'FAILED'
                ? ($data['reason'] ?: 'Simulated failure dari admin testing tool.')
                : 'Simulated callback dari admin testing tool.',
        ];

        if ($data['status'] === 'PAID') {
            $update['paid_at'] = now();
            $update['failed_at'] = null;
        } elseif ($data['status'] === 'FAILED') {
            $update['failed_at'] = now();
            $update['failed_reason'] = $data['reason'] ?: 'Simulated failure dari admin testing tool.';
            $update['paid_at'] = null;
        } else {
            $update['paid_at'] = null;
            $update['failed_at'] = null;
        }

        $row->update($update);

        return response()->json([
            'message' => 'Status test withdrawal disimulasikan ke ' . $data['status'] . '.',
            'data' => $row->fresh(['user', 'payoutAccount']),
        ]);
    }

    public function testDestroy($id)
    {
        $row = Withdrawal::where('id', $id)
            ->where('is_test', true)
            ->firstOrFail();

        $row->delete();

        return response()->json(['message' => 'Test withdrawal dihapus.']);
    }

    public function approve(Request $request, $id)
    {
        $admin = $request->user();

        $row = Withdrawal::where('id', $id)->firstOrFail();

        if ($row->status !== 'PENDING') {
            return response()->json([
                'message' => 'Status harus PENDING untuk approve.',
            ], 422);
        }

        $row->update([
            'status' => 'APPROVED',
            'admin_id' => $admin->id,
            'approved_at' => now(),
        ]);

        return response()->json([
            'message' => 'Withdraw approved',
            'data' => $row,
        ]);
    }

    public function reject(Request $request, $id)
    {
        $data = $request->validate([
            'reason' => ['required', 'string', 'max:500'],
        ]);

        $admin = $request->user();

        DB::transaction(function () use ($id, $data, $admin) {
            $row = Withdrawal::lockForUpdate()
                ->where('id', $id)
                ->firstOrFail();

            if (!in_array($row->status, ['PENDING', 'APPROVED', 'PROCESSING'], true)) {
                abort(422, 'Status harus PENDING/APPROVED/PROCESSING untuk reject.');
            }

            $user = $row->user()->lockForUpdate()->first();

            if ($user) {
                /*
                |--------------------------------------------------------------------------
                | Return funds
                |--------------------------------------------------------------------------
                | Saat user request WD, saldo biasanya sudah dipindah ke saldo_hold.
                | Kalau reject, dana dikembalikan ke saldo utama dan hold dikurangi.
                */
            $user->saldo_penarikan = (float) ($user->saldo_penarikan ?? 0) + (float) ($row->amount ?? 0);
            $user->saldo_hold = max(0, (float) ($user->saldo_hold ?? 0) - (float) ($row->amount ?? 0));
            $user->save();
            }

            $row->update([
                'status' => 'REJECTED',
                'admin_id' => $admin->id,
                'reject_reason' => $data['reason'],
            ]);
        });

        return response()->json([
            'message' => 'Withdraw rejected',
        ]);
    }

    public function markPaid(Request $request, $id)
    {
        $admin = $request->user();

        $data = $request->validate([
            'proof' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
        ]);

        DB::transaction(function () use ($request, $id, $admin, $data) {
            $row = Withdrawal::lockForUpdate()
                ->where('id', $id)
                ->firstOrFail();

            if ($row->status === 'PAID') {
                return;
            }

            if (!in_array($row->status, ['APPROVED', 'PENDING', 'PROCESSING'], true)) {
                abort(422, 'Status harus APPROVED/PENDING/PROCESSING untuk mark paid.');
            }

            $user = $row->user()->lockForUpdate()->first();

            if ($user) {
                /*
                |--------------------------------------------------------------------------
                | Finalize WD
                |--------------------------------------------------------------------------
                | Saldo utama sudah dipotong saat request WD.
                | Saat paid, saldo_hold dikurangi permanen.
                */
                $user->saldo_hold = max(0, (float) ($user->saldo_hold ?? 0) - (float) ($row->amount ?? 0));
                $user->save();
            }

            $proofUrl = $row->proof_url;

            if ($request->hasFile('proof')) {
                $path = $request->file('proof')->store('withdraw_proofs', 'public');
                $proofUrl = Storage::disk('public')->url($path);
            }

            $row->update([
                'status' => 'PAID',
                'admin_id' => $admin->id,
                'paid_at' => now(),
                'proof_url' => $proofUrl,
            ]);
        });

        return response()->json([
            'message' => 'Withdraw marked as paid',
        ]);
    }

    public function markFailed(Request $request, $id)
    {
        $data = $request->validate([
            'reason' => ['nullable', 'string', 'max:500'],
        ]);

        $admin = $request->user();

        DB::transaction(function () use ($id, $data, $admin) {
            $row = Withdrawal::lockForUpdate()
                ->where('id', $id)
                ->firstOrFail();

            if ($row->status === 'PAID') {
                abort(422, 'Withdraw PAID tidak bisa diubah menjadi FAILED.');
            }

            if ($row->status === 'FAILED') {
                return;
            }

            if (!in_array($row->status, ['PENDING', 'APPROVED', 'PROCESSING', 'REJECTED'], true)) {
                abort(422, 'Status tidak valid untuk Set FAILED.');
            }

            /*
            |--------------------------------------------------------------------------
            | Kalau belum pernah direject, kembalikan dana user
            |--------------------------------------------------------------------------
            | REJECTED biasanya sudah return funds, jadi jangan dobel balikin saldo.
            */
            if ($row->status !== 'REJECTED') {
                $user = $row->user()->lockForUpdate()->first();

                if ($user) {
                $user->saldo_penarikan = (float) ($user->saldo_penarikan ?? 0) + (float) ($row->amount ?? 0);
                $user->saldo_hold = max(0, (float) ($user->saldo_hold ?? 0) - (float) ($row->amount ?? 0));
                $user->save();
                }
            }

            $row->update([
                'status' => 'FAILED',
                'admin_id' => $admin->id,
                'reject_reason' => $data['reason'] ?: 'Set FAILED by admin',
            ]);
        });

        return response()->json([
            'message' => 'Withdraw berhasil ditandai FAILED.',
        ]);
    }
}