<?php

namespace App\Http\Controllers;

use App\Services\BayarProPayoutService;
use App\Services\BayarProService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BayarProWebhookController extends Controller
{
    /**
     * Endpoint webhook tunggal BayarPro.
     *
     * Dashboard BayarPro hanya menyediakan satu Webhook Notification URL untuk
     * semua event, jadi notifikasi deposit (payment.*) dan payout (payout.*)
     * masuk ke sini, lalu didispatch ke handler masing-masing berdasarkan event.
     */
    public function handle(
        Request $request,
        BayarProService $bayarPro,
        DepositController $deposit,
        WithdrawalController $withdrawal
    ) {
        $decoded = json_decode($request->getContent(), true) ?: [];
        $event = (string) ($decoded['event'] ?? '');

        Log::info('BayarPro webhook received', ['event' => $event]);

        if (str_starts_with($event, 'payout')) {
            return $withdrawal->bayarProPayoutCallback($request, app(BayarProPayoutService::class));
        }

        if (str_starts_with($event, 'payment')) {
            return $deposit->bayarProCallback($request, $bayarPro);
        }

        Log::warning('BayarPro webhook unknown event', ['event' => $event]);

        return response()->json(['status' => 'ignored']);
    }
}
