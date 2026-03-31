<?php

namespace App\Http\Controllers;

use App\Models\Deposit;
use App\Models\User;
use App\Models\VipRule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Services\ReferralService;

class DepositController extends Controller
{
public function index()
{
    $user = \App\Models\User::find(Auth::id());

    $deposits = Deposit::where('user_id', $user->id)
        ->latest()
        ->get();

    return view('deposit.index', compact('deposits', 'user'));
}
    public function store(Request $request)
    {
        $request->validate([
            'amount' => 'required|integer|min:10000',
        ]);

        Deposit::create([
            'user_id'  => Auth::id(),
            'order_id' => 'DEP-' . strtoupper(Str::random(10)),
            'amount'   => (int) $request->amount,
            'method'   => 'MANUAL',
            'status'   => 'UNPAID',
        ]);

        return back()->with('success', 'Invoice deposit berhasil dibuat');
    }

    public function callback($order_id)
    {
        DB::beginTransaction();

        try {
            $deposit = Deposit::where('order_id', $order_id)->lockForUpdate()->firstOrFail();

            // 1. Cegah double proses
            if ($deposit->status === 'PAID') {
                DB::rollBack();
                return back()->with('success', 'Deposit sudah diproses');
            }

            // 2. Update status deposit
            $deposit->status = 'PAID';
            $deposit->save();

            // 3. Ambil user
            $user = User::lockForUpdate()->findOrFail($deposit->user_id);

            // 4. Tambah saldo
            $oldSaldo = $user->saldo;
            $user->saldo += $deposit->amount;

            // 5. Hitung total deposit PAID
            $totalDeposit = Deposit::where('user_id', $user->id)
                ->where('status', 'PAID')
                ->sum('amount');

            // 6. Ambil VIP rules dari database
            $vipRules = VipRule::where('is_active', 1)
                ->orderBy('min_total_deposit', 'asc')
                ->get();

            $newVip = $user->vip_level;

            foreach ($vipRules as $rule) {
                if ($totalDeposit >= $rule->min_total_deposit) {
                    $newVip = $rule->vip_level;
                }
            }


            // 7. Update VIP jika naik
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
