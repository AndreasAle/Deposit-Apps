<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

/**
 * Melindungi endpoint listener (mutasi & heartbeat).
 *
 * Token dikirim HP lewat header. Wajib pakai HTTPS di produksi - token ini
 * setara kunci untuk menandai deposit lunas, jadi kalau bocor orang bisa
 * menambah saldo tanpa bayar.
 */
class ListenerToken
{
    public function handle(Request $request, Closure $next): Response
    {
        $expected = (string) config('deposit.listener.token');

        if ($expected === '') {
            Log::error('LISTENER_TOKEN belum diisi di .env - endpoint listener dimatikan');

            return response()->json([
                'message' => 'Listener belum dikonfigurasi di server.',
            ], 503);
        }

        $given = (string) $request->header('X-Auth-Token', '');

        if ($given === '') {
            $bearer = (string) $request->header('Authorization', '');

            if (stripos($bearer, 'bearer ') === 0) {
                $given = trim(substr($bearer, 7));
            }
        }

        if ($given === '' || !hash_equals($expected, $given)) {
            Log::warning('Listener token salah', ['ip' => $request->ip()]);

            return response()->json(['message' => 'Unauthorized'], 401);
        }

        return $next($request);
    }
}
