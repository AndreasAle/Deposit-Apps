<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Withdrawal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class WithdrawalAdminController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status');

        $q = Withdrawal::query()
            ->with(['user', 'payoutAccount'])
            ->latest();

        if ($status) {
            $q->where('status', $status);
        }

        return response()->json($q->paginate(30));
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
                $user->saldo = (float) ($user->saldo ?? 0) + (float) ($row->amount ?? 0);
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
                    $user->saldo = (float) ($user->saldo ?? 0) + (float) ($row->amount ?? 0);
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