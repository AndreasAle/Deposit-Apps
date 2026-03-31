<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureAdmin
{
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();

        if (!$user) {
            return redirect('/login');
        }

        // asumsi field role di users: 'admin' / 'user'
        if (($user->role ?? null) !== 'admin') {
            abort(403, 'Forbidden');
        }

        return $next($request);
    }
}
