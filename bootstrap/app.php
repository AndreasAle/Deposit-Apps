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
        | Middleware Alias
        |--------------------------------------------------------------------------
        */
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
        ]);

        /*
        |--------------------------------------------------------------------------
        | CSRF Exception
        |--------------------------------------------------------------------------
        */
        $middleware->validateCsrfTokens(except: [
            'payment/jayapay/deposit/callback',
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