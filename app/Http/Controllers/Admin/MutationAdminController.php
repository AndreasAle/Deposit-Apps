<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Deposit;
use App\Models\Mutation;
use App\Services\DepositCreditService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Review mutasi yang tidak tercocokkan otomatis.
 *
 * Sengaja JSON-only supaya tidak mengubah tampilan admin yang sudah ada.
 */
class MutationAdminController extends Controller
{
    public function index(Request $request)
    {
        $q = Mutation::query()->with('deposit.user')->latest();

        if ($status = $request->query('status')) {
            $q->where('status', $status);
        }

        if ($request->boolean('review_only')) {
            $q->whereIn('status', ['unmatched', 'needs_review']);
        }

        return response()->json([
            'summary' => [
                'matched' => Mutation::where('status', 'matched')->count(),
                'unmatched' => Mutation::where('status', 'unmatched')->count(),
                'needs_review' => Mutation::where('status', 'needs_review')->count(),
            ],
            // Deposit yang masih menunggu bayar, buat bantu admin mencocokkan.
            'pending_deposits' => Deposit::where('status', 'UNPAID')
                ->with('user:id,name,phone')
                ->latest('id')
                ->limit(50)
                ->get(['id', 'user_id', 'order_id', 'amount', 'real_amount', 'expired_at']),
            'data' => $q->paginate(30),
        ]);
    }

    /**
     * Cocokkan mutasi ke sebuah deposit secara manual, lalu tambah saldo.
     */
    public function resolve(Request $request, DepositCreditService $credit)
    {
        $data = $request->validate([
            'mutation_id' => 'required|integer|exists:mutations,id',
            'deposit_id' => 'required|integer|exists:deposits,id',
        ]);

        try {
            DB::transaction(function () use ($data, $credit) {
                $mutation = Mutation::lockForUpdate()->findOrFail($data['mutation_id']);
                $deposit = Deposit::lockForUpdate()->findOrFail($data['deposit_id']);

                if ($mutation->status === 'matched') {
                    abort(422, 'Mutasi ini sudah tercocokkan.');
                }

                if ($deposit->status === 'PAID') {
                    abort(422, 'Deposit ini sudah lunas.');
                }

                $mutation->status = 'matched';
                $mutation->deposit_id = $deposit->id;
                $mutation->candidate_deposit_id = null;
                $mutation->note = 'dicocokkan manual oleh admin';
                $mutation->save();

                $deposit->status = 'PAID';
                $deposit->paid_at = now();
                $deposit->plat_order_num = $deposit->plat_order_num ?: ('MANUAL-' . $mutation->id);
                $deposit->save();

                $credit->credit($deposit);
            });
        } catch (\Symfony\Component\HttpKernel\Exception\HttpException $e) {
            return response()->json(['message' => $e->getMessage()], $e->getStatusCode());
        }

        return response()->json(['message' => 'Mutasi berhasil dicocokkan dan saldo ditambahkan.']);
    }

    /**
     * Tandai mutasi sebagai tidak relevan (bukan pembayaran deposit).
     */
    public function dismiss(Request $request)
    {
        $data = $request->validate([
            'mutation_id' => 'required|integer|exists:mutations,id',
            'note' => 'nullable|string|max:255',
        ]);

        $mutation = Mutation::findOrFail($data['mutation_id']);

        if ($mutation->status === 'matched') {
            return response()->json(['message' => 'Mutasi yang sudah lunas tidak bisa diabaikan.'], 422);
        }

        $mutation->status = 'dismissed';
        $mutation->note = $data['note'] ?? 'diabaikan admin';
        $mutation->save();

        return response()->json(['message' => 'Mutasi diabaikan.']);
    }
}
