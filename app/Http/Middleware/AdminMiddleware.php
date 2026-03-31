<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
public function handle(Request $request, Closure $next): Response
{
    // Kalau belum login → lempar ke login
    if (!auth()->check()) {
        return redirect('/login');
    }

    // Kalau login tapi bukan admin → lempar ke dashboard user
    if (auth()->user()->role !== 'admin') {
        return redirect('/dashboard')->with('error', 'Akses ditolak');
    }

    // Kalau admin → lanjut
    return $next($request);
}
}
