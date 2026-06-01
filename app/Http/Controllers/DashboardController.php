<?php

namespace App\Http\Controllers;

use App\Models\Deposit;
use App\Models\ProductCategory;
use App\Models\User;
use App\Models\UserInvestment;
// use App\Models\VipRule;
// use App\Services\ReferralService;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if (!$user) {
            return redirect()->route('login');
        }

        $categories = ProductCategory::with(['products' => function ($q) {
            $q->where('is_active', 1);
        }])->get();

        $activeInvestmentRows = UserInvestment::query()
            ->where('user_id', $user->id)
            ->where('status', 'active')
            ->get();

        $activeInvestments = $activeInvestmentRows->keyBy('product_id');

        $totalInvestasi = (int) $activeInvestmentRows->sum('price');
        $activePlanCount = (int) $activeInvestmentRows->count();
        $totalDailyProfit = (int) $activeInvestmentRows->sum('daily_profit');
        $totalProfit = (int) $activeInvestmentRows->sum('total_profit');

        $saldoUtama = (int) ($user->saldo ?? 0);
        $saldoPenarikan = (int) ($user->saldo_penarikan ?? 0);
        $saldoHold = (int) ($user->saldo_hold ?? 0);
        $saldoPenarikanTotal = (int) ($user->saldo_penarikan_total ?? 0);

        return view('dashboard', compact(
            'user',
            'categories',
            'activeInvestments',
            'saldoUtama',
            'saldoPenarikan',
            'saldoHold',
            'saldoPenarikanTotal',
            'totalInvestasi',
            'activePlanCount',
            'totalDailyProfit',
            'totalProfit'
        ));
    }

public function callback($order_id)
{
    DB::beginTransaction();

    try {
        $deposit = Deposit::where('order_id', $order_id)
            ->lockForUpdate()
            ->firstOrFail();

        if ($deposit->status === 'PAID') {
            DB::rollBack();
            return back()->with('success', 'Deposit sudah diproses');
        }

        $deposit->status = 'PAID';
        $deposit->paid_at = now();
        $deposit->save();

        $user = User::where('id', $deposit->user_id)
            ->lockForUpdate()
            ->firstOrFail();

        /*
        |--------------------------------------------------------------------------
        | Deposit hanya menambah saldo utama
        |--------------------------------------------------------------------------
        | Deposit tidak menaikkan VIP.
        | Deposit tidak memberi komisi referral.
        */
        $user->saldo = (int) $user->saldo + (int) $deposit->amount;
        $user->save();

        DB::commit();

        return back()->with('success', 'Deposit berhasil, saldo diperbarui');
    } catch (\Throwable $e) {
        DB::rollBack();
        report($e);

        return back()->with('error', 'Terjadi kesalahan saat memproses deposit');
    }
}
}