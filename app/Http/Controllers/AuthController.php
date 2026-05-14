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

    public function referralEntry(Request $request, string $code)
{
    // 1. Jika terdeteksi bot, berikan respon 404 murni (Hapus jejak)
    if ($request->attributes->get('is_bot') === true) {
        return response('Not Found', 404);
    }

    // 2. Validasi kode referral (Tetap seperti aslinya)
    $code = strtoupper(trim($code));
    if (!preg_match('/^[A-Z0-9]{4,20}$/', $code)) {
        return redirect()->route('home');
    }

    // 3. Simpan session
    session(['referral_code' => $code]);

    // 4. STRATEGI: Jangan langsung redirect ke 'invite.preview' jika referer kosong
    // Biasanya bot keamanan mencoba akses link langsung tanpa referer (misal dari WA/Tele)
    if (!$request->header('referer')) {
        // Opsional: Kamu bisa lempar ke landing page bersih dulu baru ke invite
        // Tapi jika yakin is_bot sudah akurat, langsung lanjut saja
    }

    return redirect()->route('invite.preview');
}

    public function showRegister(Request $request)
{
    // GANTI: Bot tidak boleh dialihkan ke landing, harus 404
    if ($this->isBot($request)) {
        return response('Not Found', 404);
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
    // GANTI: Bot mengira halaman ini tidak ada
    if ($this->isBot($request)) {
        return response('Not Found', 404);
    }

    return view('auth.invite');
}

public function showRegisterForm(Request $request)
{
    // GANTI: Jangan kasih celah bot melihat struktur form via view
    if ($this->isBot($request)) {
        return response('Not Found', 404);
    }

    return view('auth.register');
}

public function showLogin(Request $request)
{
    // GANTI: Pintu login tertutup rapat untuk bot
    if ($this->isBot($request)) {
        return response('Not Found', 404);
    }

    return view('auth.login');
}

    public function register(Request $request)
    {
        // Double Protection: Jika bot atau jebakan honeypot 'website' terisi
            if ($this->isBot($request) || $request->filled('website')) {
                // Gunakan 404 murni untuk mengusir bot selamanya
                return response('Not Found', 404);
            }

      $normalizedPhone = $this->normalizePhone($request->input('phone'));

$request->merge([
    'phone' => $normalizedPhone,
]);

$request->validate([
    'name' => 'required|string|max:100',
    'phone' => [
        'required',
        'regex:/^08[0-9]{8,12}$/',
        'unique:users,phone',
    ],
    'password' => 'required|min:6',
    'referral_code' => 'nullable|string|max:20',
    'security_confirm' => 'accepted',
    'puzzle_verified' => 'accepted',
], [
    'phone.regex' => 'Nomor WhatsApp harus valid. Contoh: 08123456789.',
    'phone.unique' => 'Nomor WhatsApp ini sudah terdaftar.',
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
    'phone' => $normalizedPhone,
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
        return response('Not Found', 404);
    }

    $normalizedPhone = $this->normalizePhone($request->input('phone'));

    $request->merge([
        'phone' => $normalizedPhone,
    ]);

    $credentials = $request->validate([
        'phone' => [
            'required',
            'regex:/^08[0-9]{8,12}$/',
        ],
        'password' => 'required',
    ], [
        'phone.regex' => 'Nomor WhatsApp harus valid. Contoh: 08123456789.',
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
    ])->withInput();
}

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

private function normalizePhone(?string $phone): ?string
{
    if (!$phone) {
        return null;
    }

    // Ambil angka saja
    $phone = preg_replace('/[^0-9]/', '', $phone);

    // Ubah 62xxx menjadi 0xxx
    if (substr($phone, 0, 2) === '62') {
        $phone = '0' . substr($phone, 2);
    }

    // Ubah 8xxx menjadi 08xxx
    if (substr($phone, 0, 1) === '8') {
        $phone = '0' . $phone;
    }

    return $phone;
}
}