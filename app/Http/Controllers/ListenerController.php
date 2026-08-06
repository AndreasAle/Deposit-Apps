<?php

namespace App\Http\Controllers;

use App\Services\MutationMatcher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Endpoint yang ditembak HP listener (MacroDroid).
 * Semua route di sini dilindungi middleware listener.token.
 */
class ListenerController extends Controller
{
    /**
     * Terima satu notifikasi "uang masuk".
     */
    public function mutasi(Request $request, MutationMatcher $matcher)
    {
        $data = $request->validate([
            'ext_id' => 'required|string|max:150',
            'amount' => 'nullable|integer|min:1',
            'raw' => 'nullable|string|max:2000',
            'text' => 'nullable|string|max:2000',
            'source' => 'nullable|string|max:64',
            'device' => 'nullable|string|max:64',
            'notified_at' => 'nullable|date',
        ]);

        try {
            $result = $matcher->handle([
                'ext_id' => $data['ext_id'],
                'amount' => $data['amount'] ?? null,
                'raw' => $data['raw'] ?? $data['text'] ?? null,
                'source' => $data['source'] ?? 'listener',
                'device' => $data['device'] ?? null,
                'notified_at' => $data['notified_at'] ?? null,
            ]);
        } catch (\Throwable $e) {
            Log::error('Gagal memproses mutasi listener', [
                'message' => $e->getMessage(),
                'ext_id' => $data['ext_id'],
            ]);

            // 500 supaya MacroDroid/HP bisa mencoba ulang. Aman karena
            // pemrosesannya idempoten.
            return response()->json(['message' => 'Gagal memproses mutasi'], 500);
        }

        $this->touchHeartbeat($data['device'] ?? 'hp-listener', 'kirim mutasi');

        $mutation = $result['mutation'];

        return response()->json([
            'ok' => true,
            'duplicate' => $result['duplicate'],
            'status' => $mutation->status,
            'deposit_id' => $mutation->deposit_id,
            'amount' => $mutation->amount,
            'note' => $mutation->note,
        ]);
    }

    /**
     * Denyut nadi dari HP. Kalau berhenti, admin tahu konfirmasi otomatis mati.
     */
    public function heartbeat(Request $request)
    {
        $data = $request->validate([
            'device' => 'nullable|string|max:64',
            'info' => 'nullable|string|max:255',
        ]);

        $this->touchHeartbeat($data['device'] ?? 'hp-listener', $data['info'] ?? null);

        return response()->json(['ok' => true]);
    }

    /**
     * Status listener + ringkasan antrian review. Dipakai admin.
     */
    public function status()
    {
        $timeout = (int) config('deposit.listener.heartbeat_timeout', 120);

        $devices = DB::table('listener_heartbeats')
            ->orderByDesc('last_seen')
            ->get()
            ->map(function ($row) use ($timeout) {
                $age = now()->diffInSeconds($row->last_seen, true);

                return [
                    'device' => $row->device,
                    'last_seen' => $row->last_seen,
                    'age' => (int) $age,
                    'online' => $age <= $timeout,
                    'info' => $row->info,
                ];
            });

        return response()->json([
            // Listener hanya relevan untuk saluran QRIS statis; kalau saluran
            // itu dimatikan, admin perlu tahu bahwa antrian ini tidak terpakai.
            'channel' => \App\Services\DepositChannels::QRIS_STATIS,
            'channel_enabled' => \App\Services\DepositChannels::isEnabled(
                \App\Services\DepositChannels::QRIS_STATIS
            ),
            'online' => $devices->contains('online', true),
            'never_connected' => $devices->isEmpty(),
            'timeout' => $timeout,
            'devices' => $devices,
            'needs_review' => \App\Models\Mutation::whereIn('status', ['unmatched', 'needs_review'])->count(),
        ]);
    }

    private function touchHeartbeat(string $device, ?string $info): void
    {
        DB::table('listener_heartbeats')->updateOrInsert(
            ['device' => $device],
            ['last_seen' => now(), 'info' => $info]
        );
    }
}
