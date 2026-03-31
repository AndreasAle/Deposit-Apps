<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    // LIST USER (sudah kamu punya, ini buat rapi aja)
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

    // UPDATE VIP (OVERRIDE)
    public function updateVip(Request $request, $id)
    {
        $request->validate([
            'vip_level' => 'required|integer|min:0|max:10'
        ]);

        $user = User::findOrFail($id);

        $oldVip = $user->vip_level;
        $user->vip_level = $request->vip_level;
        $user->save();

        // (nanti bisa masuk ke table logs)
        // AdminLog::create(...)

        return back()->with('success', "VIP user berhasil diubah dari VIP $oldVip ke VIP {$user->vip_level}");
    }

    public function updateSaldo(Request $request, $id)
    {
        $request->validate([
            'amount' => 'required|numeric'
        ]);

        $user = User::findOrFail($id);

        $oldSaldo = $user->saldo;
        $user->saldo += $request->amount; // bisa + atau -
        $user->save();

        activity_log(
            Auth::id(),
            'ADMIN_UPDATE_SALDO',
            "User #{$user->id} saldo {$oldSaldo} → {$user->saldo}"
        );

        return back()->with('success', 'Saldo berhasil diperbarui');
    }
}
