<?php

namespace App\Http\Controllers;

use App\Models\ProductCategory;
use App\Models\UserInvestment;
use App\Services\ReferralService;

// callback() needs these:
use Illuminate\Support\Facades\DB;
use App\Models\Deposit;
use App\Models\User;
use App\Models\VipRule;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $categories = ProductCategory::with(['products' => function ($q) {
            $q->where('is_active', 1);
        }])->get();

        // ✅ INI YANG DITAMBAH: ambil investasi aktif user, mapping by product_id
        $activeInvestments = UserInvestment::where('user_id', $user->id)
            ->where('status', 'active')
            ->get()
            ->keyBy('product_id');

        return view('dashboard', compact('user', 'categories', 'activeInvestments'));
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
            $deposit->save();

            $user = User::lockForUpdate()->findOrFail($deposit->user_id);

            $user->saldo += $deposit->amount;

            // VIP calc
            $totalDeposit = Deposit::where('user_id', $user->id)
                ->where('status', 'PAID')
                ->sum('amount');

            $vipRules = VipRule::where('is_active', 1)
                ->orderBy('min_total_deposit', 'asc')
                ->get();

            $newVip = $user->vip_level;
            foreach ($vipRules as $rule) {
                if ($totalDeposit >= $rule->min_total_deposit) {
                    $newVip = $rule->vip_level;
                }
            }

            if ($newVip > $user->vip_level) {
                $user->vip_level = $newVip;
            }

            $user->save();

            // ✅ REFERRAL COMMISSION: deposit 5%
            (new ReferralService())->give(
                $user,
                'deposit',
                (int) $deposit->id,
                (float) $deposit->amount,
                0.05
            );

            DB::commit();
            return back()->with('success', 'Deposit berhasil, saldo & VIP diperbarui');
        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);
            return back()->with('error', 'Terjadi kesalahan saat memproses deposit');
        }
    }
}
