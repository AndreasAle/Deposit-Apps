<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityCloaking
{
    public function handle(Request $request, Closure $next): Response
    {
        $userAgent = strtolower($request->header('User-Agent', ''));

        // 1. Daftar Bot Signals (Hanya untuk Bot/Scanner asli)
        // 'inspect' dan 'lighthouse' dihapus agar kamu bisa cek via devtools tanpa terblokir
        $botSignals = [
            'googlebot', 'bingbot', 'crawler', 'spider', 'curl', 'wget', 
            'python', 'nikto', 'scan', 'headless'
        ];

        $isBot = false;

        // 2. Cek User-Agent
        if (empty($userAgent)) {
            $isBot = true;
        } else {
            foreach ($botSignals as $signal) {
                if (str_contains($userAgent, $signal)) {
                    $isBot = true;
                    break;
                }
            }
        }

        /**
         * 3. Logic Tambahan (DIPERBAIKI)
         * Sebelumnya: empty($referer) langsung dianggap bot.
         * Sekarang: Kita hapus pengecekan empty referer karena user asli sering 
         * klik link dari aplikasi (WA/Tele) yang tidak selalu mengirim referer.
         */
         
        // Simpan status bot ke atribut request agar bisa dibaca AuthController
        $request->attributes->set('is_bot', $isBot);

        return $next($request);
    }
}