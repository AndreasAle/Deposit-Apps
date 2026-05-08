<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Support\ReferralCode;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Clean Referral Entry
    |--------------------------------------------------------------------------
    | Link publik yang dibagikan:
    | /r/KODEREFERRAL
    |
    | Tidak langsung membuka form register.
    | Kode disimpan ke session, lalu user diarahkan ke halaman undangan/informasi.
    */
    public function referralEntry(Request $request, string $code)
    {
        $code = strtoupper(trim($code));

        if (!preg_match('/^[A-Z0-9]{4,20}$/', $code)) {
            return redirect()->route('home');
        }

        session([
            'referral_code' => $code,
        ]);

        return redirect()->route('invite.preview');
    }

    /*
    |--------------------------------------------------------------------------
    | Legacy Register URL
    |--------------------------------------------------------------------------
    | Kalau link lama /register?ref=KODE masih tersebar, jangan tampilkan form.
    | Simpan referral ke session lalu arahkan ke halaman undangan.
    */
    public function showRegister(Request $request)
    {
        if ($request->filled('ref')) {
            $ref = strtoupper(trim($request->query('ref')));

            if (preg_match('/^[A-Z0-9]{4,20}$/', $ref)) {
                session([
                    'referral_code' => $ref,
                ]);
            }

            return redirect()->route('invite.preview');
        }

        return redirect()->route('invite.preview');
    }

    public function showInvite(Request $request)
    {
        return view('auth.invite');
    }

    public function showRegisterForm(Request $request)
    {
        return view('auth.register');
    }

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
    'security_confirm' => 'accepted',
    'puzzle_verified' => 'accepted',
]);

        /*
        |--------------------------------------------------------------------------
        | Referral Code Lock
        |--------------------------------------------------------------------------
        | Prioritas utama tetap session.
        | Input referral dari form hanya fallback kalau session kosong.
        */
        $refCode = session('referral_code') ?: $request->input('referral_code');

        $refCode = $refCode
            ? strtoupper(trim($refCode))
            : null;

        $referrer = null;

        if ($refCode) {
            if (!preg_match('/^[A-Z0-9]{4,20}$/', $refCode)) {
                return back()
                    ->withErrors(['referral_code' => 'Kode referral tidak valid'])
                    ->withInput();
            }

            $referrer = User::where('referral_code', $refCode)->first();

            if (!$referrer) {
                return back()
                    ->withErrors(['referral_code' => 'Kode referral tidak ditemukan'])
                    ->withInput();
            }

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

        session()->forget('referral_code');

        Auth::login($user);

        return redirect('/dashboard');
    }

    public function showLogin()
    {
        return view('auth.login');
    }

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

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}