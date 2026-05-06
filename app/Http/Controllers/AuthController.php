<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Support\ReferralCode;

class AuthController extends Controller
{
    // FORM REGISTER
    public function showRegister(Request $request)
    {
        if ($request->filled('ref')) {
            session([
                'referral_code' => strtoupper(trim($request->query('ref')))
            ]);
        }

        return view('auth.register');
    }

    // PROSES REGISTER
    public function register(Request $request)
    {
        // Honeypot anti bot
        if ($request->filled('website')) {
            return back();
        }

        $request->validate([
            'name' => 'required|string|max:100',
            'phone' => 'required|string|unique:users,phone',
            'password' => 'required|min:6',
            'referral_code' => 'nullable|string|max:20',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Referral Code Lock
        |--------------------------------------------------------------------------
        | Kalau user datang dari /register?ref=XXXXX,
        | kode referral disimpan di session.
        | Jadi walaupun input referral dihapus/diubah dari frontend,
        | backend tetap pakai kode dari session.
        */
        $refCode = session('referral_code')
            ?: $request->input('referral_code');

        $refCode = $refCode
            ? strtoupper(trim($refCode))
            : null;

        $referrer = null;

        if ($refCode) {
            $referrer = User::where('referral_code', $refCode)->first();

            if (!$referrer) {
                return back()
                    ->withErrors(['referral_code' => 'Kode referral tidak ditemukan'])
                    ->withInput();
            }

            // Blok admin jadi referrer
            if ($referrer->role === 'admin') {
                return back()
                    ->withErrors(['referral_code' => 'Kode referral tidak valid'])
                    ->withInput();
            }
        }

        $user = User::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),

            'saldo' => 0,
            'saldo_penarikan' => 0,
            'saldo_penarikan_total' => 0,
            'saldo_hold' => 0,

            'vip_level' => 0,
            'role' => 'user',

            'referral_code' => ReferralCode::generateUnique(10),
            'referred_by_user_id' => $referrer?->id,
        ]);

        /*
        |--------------------------------------------------------------------------
        | Hapus session referral setelah berhasil daftar
        |--------------------------------------------------------------------------
        | Biar kode referral tidak kebawa ke register berikutnya.
        */
        session()->forget('referral_code');

        Auth::login($user);

        return redirect('/dashboard');
    }

    // FORM LOGIN
    public function showLogin()
    {
        return view('auth.login');
    }

    // PROSES LOGIN
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'phone' => 'required',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user();

            if ($user && $user->role === 'admin') {
                return redirect('/admin');
            }

            return redirect('/dashboard');
        }

        return back()->withErrors([
            'phone' => 'Nomor HP atau password salah',
        ]);
    }

    // LOGOUT
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}