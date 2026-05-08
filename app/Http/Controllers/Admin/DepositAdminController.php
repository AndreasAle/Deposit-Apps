<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Deposit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DepositAdminController extends Controller
{
    public function page()
    {
        return view('admin.deposits.index');
    }

    public function index(Request $request)
    {
        $status = $request->query('status');
        $keyword = $request->query('q');

        $q = Deposit::query()
            ->with('user')
            ->latest();

        if ($status) {
            $q->where('status', $status);
        }

        if ($keyword) {
            $q->where(function ($query) use ($keyword) {
                $query
                    ->where('order_id', 'like', "%{$keyword}%")
                    ->orWhere('plat_order_num', 'like', "%{$keyword}%")
                    ->orWhere('method', 'like', "%{$keyword}%")
                    ->orWhere('selected_channel', 'like', "%{$keyword}%")
                    ->orWhereHas('user', function ($userQuery) use ($keyword) {
                        $userQuery
                            ->where('name', 'like', "%{$keyword}%")
                            ->orWhere('phone', 'like', "%{$keyword}%");
                    });
            });
        }

        $summary = [
            'total' => (clone $q)->count(),
            'paid' => Deposit::where('status', 'PAID')->count(),
            'unpaid' => Deposit::where('status', 'UNPAID')->count(),
            'failed' => Deposit::where('status', 'FAILED')->count(),
            'paid_amount' => Deposit::where('status', 'PAID')->sum('amount'),
        ];

        return response()->json([
            'summary' => $summary,
            'data' => $q->paginate(30),
        ]);
    }

    public function markPaid(Request $request, $id)
    {
        DB::transaction(function () use ($id) {
            $deposit = Deposit::lockForUpdate()
                ->with('user')
                ->findOrFail($id);

            if ($deposit->status === 'PAID') {
                return;
            }

            $deposit->status = 'PAID';
            $deposit->paid_at = now();
            $deposit->save();

            $user = $deposit->user()->lockForUpdate()->first();

            if ($user) {
                $user->saldo = (float) $user->saldo + (float) $deposit->amount;
                $user->save();
            }
        });

        return response()->json([
            'message' => 'Deposit berhasil ditandai PAID.',
        ]);
    }

    public function markFailed(Request $request, $id)
    {
        $deposit = Deposit::findOrFail($id);

        if ($deposit->status === 'PAID') {
            return response()->json([
                'message' => 'Deposit PAID tidak bisa diubah menjadi FAILED.',
            ], 422);
        }

        $deposit->status = 'FAILED';
        $deposit->save();

        return response()->json([
            'message' => 'Deposit berhasil ditandai FAILED.',
        ]);
    }
}