<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        
        // 1. REGISTRASI MIDDLEWARE GLOBAL
        // Gunakan satu nama saja yang sudah pasti file-nya ada
        $middleware->web(append: [
            \App\Http\Middleware\SecurityCloaking::class,
        ]);

        /*
        |--------------------------------------------------------------------------
        | Trusted Proxies (di belakang Alibaba WAF)
        |--------------------------------------------------------------------------
        | Supaya Laravel membaca IP user asli & skema (https) dari header
        | X-Forwarded-* yang dikirim WAF — penting untuk remoteip Turnstile
        | dan mencegah salah-deteksi http/https. Origin server WAJIB difirewall
        | agar hanya bisa diakses lewat WAF (kalau tidak, header bisa dipalsukan).
        */
        $middleware->trustProxies(
            at: '*',
            headers: Request::HEADER_X_FORWARDED_FOR
                | Request::HEADER_X_FORWARDED_HOST
                | Request::HEADER_X_FORWARDED_PORT
                | Request::HEADER_X_FORWARDED_PROTO
        );

        /*
        |--------------------------------------------------------------------------
        | Middleware Alias
        |--------------------------------------------------------------------------
        */
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            'listener.token' => \App\Http\Middleware\ListenerToken::class,
        ]);

        /*
        |--------------------------------------------------------------------------
        | CSRF Exception
        |--------------------------------------------------------------------------
        */
        $middleware->validateCsrfTokens(except: [
            'api/bankpay/*',
            'api/listener/*',
        ]);

        // 2. LOGIKA REDIRECT UNTUK GUEST
        $middleware->redirectGuestsTo('/login');

    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Logika penanganan error agar tidak membocorkan stack trace ke bot
        $exceptions->render(function (\Throwable $e, Request $request) {
            if ($e instanceof \Symfony\Component\HttpKernel\Exception\HttpException && $e->getStatusCode() === 404) {
                // Pastikan file views/errors/custom_404.blade.php sudah ada
                return response()->view('errors.custom_404', [], 404);
            }
        });
    })
    ->create();