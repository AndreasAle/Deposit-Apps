<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    // LIST USER
    public function index()
    {
        $users = User::latest()->get();

        return view('admin.users.index', compact('users'));
    }

    // DETAIL USER
    public function show($id)
    {
        $user = User::findOrFail($id);

        return view('admin.users.show', compact('user'));
    }

    // UPDATE VIP
    public function updateVip(Request $request, $id)
    {
        $request->validate([
            'vip_level' => 'required|integer|min:0|max:10',
        ]);

        $user = User::findOrFail($id);

        $oldVip = (int) ($user->vip_level ?? 0);
        $user->vip_level = (int) $request->vip_level;
        $user->save();

        return back()->with('success', "VIP user berhasil diubah dari VIP {$oldVip} ke VIP {$user->vip_level}");
    }

    // UPDATE SALDO UTAMA
    public function updateSaldo(Request $request, $id)
    {
        $request->validate([
            'amount' => 'required|numeric',
        ]);

        $user = User::findOrFail($id);

        $amount = (float) $request->amount;
        $oldSaldo = (float) ($user->saldo ?? 0);

        $newSaldo = $oldSaldo + $amount;

        if ($newSaldo < 0) {
            return back()->with('error', 'Saldo utama tidak boleh minus.');
        }

        $user->saldo = $newSaldo;
        $user->save();

        if (function_exists('activity_log')) {
            activity_log(
                Auth::id(),
                'ADMIN_UPDATE_SALDO_UTAMA',
                "User #{$user->id} saldo utama {$oldSaldo} → {$user->saldo}"
            );
        }

        return back()->with('success', 'Saldo utama berhasil diperbarui');
    }

    // UPDATE SALDO PENARIKAN
    public function updateSaldoPenarikan(Request $request, $id)
    {
        $request->validate([
            'amount' => 'required|numeric',
        ]);

        $user = User::findOrFail($id);

        $amount = (float) $request->amount;
        $oldSaldoPenarikan = (float) ($user->saldo_penarikan ?? 0);
        $oldSaldoPenarikanTotal = (float) ($user->saldo_penarikan_total ?? 0);

        $newSaldoPenarikan = $oldSaldoPenarikan + $amount;

        if ($newSaldoPenarikan < 0) {
            return back()->with('error', 'Saldo penarikan tidak boleh minus.');
        }

        $user->saldo_penarikan = $newSaldoPenarikan;

        /*
         * saldo_penarikan_total = histori total saldo tarik yang pernah ditambahkan.
         * Jadi hanya naik kalau admin menambahkan saldo penarikan.
         * Kalau admin mengurangi saldo, total histori jangan ikut turun.
         */
        if ($amount > 0) {
            $user->saldo_penarikan_total = $oldSaldoPenarikanTotal + $amount;
        }

        $user->save();

        if (function_exists('activity_log')) {
            activity_log(
                Auth::id(),
                'ADMIN_UPDATE_SALDO_PENARIKAN',
                "User #{$user->id} saldo penarikan {$oldSaldoPenarikan} → {$user->saldo_penarikan}"
            );
        }

        return back()->with('success', 'Saldo penarikan berhasil diperbarui');
    }
}