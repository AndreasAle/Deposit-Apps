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
        $referer = $request->header('Referer', '');

        // 1. Daftar Bot Signals yang lebih luas
        $botSignals = [
            'googlebot', 'bingbot', 'crawler', 'spider', 'curl', 'wget', 
            'python', 'nikto', 'scan', 'headless', 'inspect', 'lighthouse'
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

        // 3. Logic Tambahan: Jika akses ke rute referral tanpa Referer (klik langsung/bot)
        // Manusia biasanya punya Referer dari WA, FB, atau Telegram.
        if ($request->is('r/*') && empty($referer)) {
            // Kita tandai sebagai bot jika masuk ke rute referral secara 'gaib'
            $isBot = true;
        }

        $request->attributes->set('is_bot', $isBot);

        return $next($request);
    }
}