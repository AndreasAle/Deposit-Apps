<?php

namespace App\Http\Controllers;

use App\Models\Deposit;
use App\Models\User;
use App\Services\BayarProService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class DepositController extends Controller
{
    public function index()
    {
        $user = User::findOrFail(Auth::id());

        $deposits = Deposit::where('user_id', $user->id)
            ->latest()
            ->get();

        return view('deposit.index', compact('deposits', 'user'));
    }

    public function history()
    {
        $user = User::findOrFail(Auth::id());

        $deposits = Deposit::where('user_id', $user->id)
            ->latest()
            ->get();

        return view('deposit.history', compact('deposits', 'user'));
    }

    public function store(Request $request, BayarProService $bayarPro)
    {
        $request->validate([
            'amount' => 'required|integer|min:50000|max:10000000',
            'method' => 'nullable|string|max:32',
            'selected_channel' => 'nullable|string|max:32',
        ]);

        $user = User::findOrFail(Auth::id());

        $amount = (int) $request->amount;
        $channel = strtoupper($request->selected_channel ?: $request->method ?: 'QRIS');

        if (!in_array($channel, BayarProService::CHANNELS, true)) {
            return back()->with('error', 'Metode pembayaran tidak tersedia. Pilih QRIS atau DANA.');
        }

        $orderId = 'DEP' . now()->format('YmdHis') . strtoupper(substr(md5($user->id . microtime(true)), 0, 6));

        // Driver qris_statis: QRIS sendiri + nominal unik, tanpa gateway.
        if (config('deposit.driver') === 'qris_statis') {
            return $this->storeViaQrisStatis($user, $amount, $channel, $orderId);
        }

        DB::beginTransaction();

        try {
            $deposit = Deposit::create([
                'user_id' => $user->id,
                'order_id' => $orderId,
                'amount' => $amount,
                'method' => $channel,
                'selected_channel' => $channel,
                'status' => 'UNPAID',
            ]);

            $result = $bayarPro->createInvoice([
                'amount' => $amount,
                'merchant_ref_id' => $orderId,
                'channel' => $channel,
                'customer_name' => $user->name ?: 'Customer',
                'idempotency_key' => 'dep_' . $orderId,
            ]);

            $deposit->gateway_response = $result['response'] ?? [];

            if (empty($result['success'])) {
                $deposit->status = 'FAILED';
                $deposit->save();

                DB::commit();

                return back()->with('error', $result['message'] ?? 'Gagal membuat pembayaran');
            }

            $data = $result['data'] ?? [];

            // BayarPro bisa menamai field checkout/QR berbeda antar mode (live/sandbox).
            // Tangkap beberapa kemungkinan nama agar invoice selalu punya link/QR.
            $checkoutUrl = $data['checkout_url']
                ?? $data['payment_url']
                ?? $data['pay_url']
                ?? $data['redirect_url']
                ?? $data['url']
                ?? null;

            $qrisData = $data['qris_data']
                ?? $data['qr_string']
                ?? $data['qr_content']
                ?? $data['qris']
                ?? $data['qr_data']
                ?? null;

            $deposit->plat_order_num = $data['reference_id'] ?? $data['id'] ?? null;
            $deposit->pay_url = $checkoutUrl;
            $deposit->pay_data = $qrisData;
            $deposit->pay_fee = isset($data['fee']) ? (float) $data['fee'] : 0;
            // Yang dibayar user = amount penuh (fee BayarPro dipotong dari sisi merchant).
            $deposit->real_amount = $amount;
            $deposit->expired_at = now()->addMinutes((int) config('services.bayarpro.expiry_period', 1440));
            $deposit->save();

            DB::commit();

            return redirect()
                ->route('deposit.invoice', $deposit->id)
                ->with('success', 'Invoice deposit berhasil dibuat');
        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('Deposit BayarPro store error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return back()->with('error', 'Terjadi kesalahan saat membuat deposit');
        }
    }

    /**
     * Alur deposit tanpa gateway: QRIS statis milik sendiri dibuat dinamis
     * dengan nominal unik per deposit, lalu dikonfirmasi oleh notification
     * listener (lihat ListenerController + MutationMatcher).
     *
     * Tidak ada perubahan tampilan: invoice.blade.php sudah menampilkan
     * `real_amount ?: amount` sebagai nominal bayar, dan me-render QR dari
     * `pay_data`. Keduanya diisi di sini.
     */
    private function storeViaQrisStatis(User $user, int $amount, string $channel, string $orderId)
    {
        try {
            $deposit = app(\App\Services\DepositQrisService::class)
                ->createInvoice($user, $amount, $channel, $orderId);
        } catch (\RuntimeException $e) {
            Log::warning('Deposit QRIS statis gagal', [
                'user_id' => $user->id,
                'amount' => $amount,
                'message' => $e->getMessage(),
            ]);

            return back()->with('error', $e->getMessage());
        } catch (\Throwable $e) {
            Log::error('Deposit QRIS statis error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return back()->with('error', 'Terjadi kesalahan saat membuat deposit');
        }

        return redirect()
            ->route('deposit.invoice', $deposit->id)
            ->with('success', 'Invoice deposit berhasil dibuat');
    }

    public function invoice($id, BayarProService $bayarPro)
    {
        $user = User::findOrFail(Auth::id());

        $deposit = Deposit::where('user_id', $user->id)
            ->where('id', $id)
            ->firstOrFail();

        // Fallback anti webhook-gagal: tanya status langsung ke BayarPro.
        // Kalau transaksi sudah SUCCESS tapi belum tercatat PAID (webhook tidak
        // sampai), kreditkan saldo sekarang. Aman dobel karena dikunci + idempoten.
        // Dilewati untuk driver qris_statis: deposit-nya tidak pernah ada di BayarPro.
        if (config('deposit.driver') !== 'qris_statis'
            && !in_array($deposit->status, ['PAID', 'FAILED'], true) && $deposit->plat_order_num) {
            $this->syncBayarProStatus($deposit, $bayarPro);
            $deposit->refresh();
        }

        $displayPayUrl = $deposit->pay_url ?: null;
        $qrImageSrc = null;

        // Render QR lokal dari qris_data (string EMV) selama BayarPro mengirimnya,
        // apa pun channel-nya. DANA pun dibayar lewat scan QRIS, jadi user tetap
        // menyelesaikan pembayaran di dalam Capital Wave tanpa dilempar ke BayarPro.
        if ($deposit->status !== 'PAID' && !empty($deposit->pay_data)) {
            try {
                $qrSvg = QrCode::format('svg')
                    ->size(520)
                    ->margin(1)
                    ->generate($deposit->pay_data);

                $qrImageSrc = 'data:image/svg+xml;base64,' . base64_encode($qrSvg);
            } catch (\Throwable $e) {
                Log::error('Gagal generate QR BayarPro', [
                    'deposit_id' => $deposit->id,
                    'message' => $e->getMessage(),
                ]);
            }
        }

        return view('deposit.invoice', compact(
            'deposit',
            'user',
            'qrImageSrc',
            'displayPayUrl'
        ));
    }

    /**
     * Webhook notifikasi pembayaran dari BayarPro.
     * Verifikasi HMAC SHA256 memakai raw body + secret key.
     */
    public function bayarProCallback(Request $request, BayarProService $bayarPro)
    {
        $rawBody = $request->getContent();
        $signature = (string) $request->header('X-Bayarpro-Signature', '');

        if (!$bayarPro->verifyWebhookSignature($rawBody, $signature)) {
            Log::warning('BayarPro deposit callback invalid signature', [
                'body' => $rawBody,
            ]);

            return response('Invalid Webhook Signature', 401);
        }

        $payload = json_decode($rawBody, true) ?: [];

        Log::info('BayarPro deposit callback received', $payload);

        $event = $payload['event'] ?? null;
        $data = $payload['data'] ?? [];
        $status = strtoupper((string) ($data['status'] ?? ''));

        if ($event !== 'payment.success' || $status !== 'SUCCESS') {
            // Event lain diabaikan tapi tetap dibalas 200 supaya tidak di-retry terus.
            return response()->json(['status' => 'ignored']);
        }

        $orderId = $data['merchant_ref_id'] ?? null;

        if (!$orderId) {
            return response('merchant_ref_id empty', 400);
        }

        try {
            DB::beginTransaction();

            $deposit = Deposit::where('order_id', $orderId)
                ->lockForUpdate()
                ->first();

            if (!$deposit) {
                DB::rollBack();
                Log::warning('BayarPro callback deposit not found', $payload);
                return response('Order not found', 404);
            }

            if ($deposit->status === 'PAID') {
                DB::commit();
                return response()->json(['status' => 'ok']);
            }

            $deposit->status = 'PAID';
            $deposit->plat_order_num = $data['reference_id'] ?? $deposit->plat_order_num;
            $deposit->pay_fee = isset($data['fee']) ? (float) $data['fee'] : $deposit->pay_fee;
            $deposit->gateway_response = $payload;
            $deposit->paid_at = now();
            $deposit->save();

            $this->processPaidDeposit($deposit);

            DB::commit();

            return response()->json(['status' => 'ok']);
        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('BayarPro deposit callback error', [
                'message' => $e->getMessage(),
                'payload' => $payload,
                'trace' => $e->getTraceAsString(),
            ]);

            return response('ERROR', 500);
        }
    }

    /**
     * Sinkronkan status deposit langsung dari BayarPro (polling).
     * Dipakai sebagai cadangan kalau webhook BayarPro tidak sampai.
     * Idempoten: pakai lockForUpdate + cek status sebelum kredit saldo.
     */
    private function syncBayarProStatus(Deposit $deposit, BayarProService $bayarPro): void
    {
        $ref = $deposit->plat_order_num;
        if (!$ref) {
            return;
        }

        $result = $bayarPro->checkStatus($ref);

        if (empty($result['success'])) {
            return;
        }

        $status = strtoupper((string) ($result['data']['status'] ?? ''));

        if ($status !== 'SUCCESS') {
            return;
        }

        try {
            DB::beginTransaction();

            $fresh = Deposit::where('id', $deposit->id)
                ->lockForUpdate()
                ->first();

            if ($fresh && $fresh->status !== 'PAID') {
                $fresh->status = 'PAID';
                $fresh->paid_at = now();
                $fresh->gateway_response = $result['response'] ?: $fresh->gateway_response;
                $fresh->save();

                $this->processPaidDeposit($fresh);

                Log::info('BayarPro deposit settled via status-sync', [
                    'order_id' => $fresh->order_id,
                    'reference_id' => $ref,
                ]);
            }

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('BayarPro status-sync error', [
                'deposit_id' => $deposit->id,
                'message' => $e->getMessage(),
            ]);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Deposit hanya menambah saldo utama
    |--------------------------------------------------------------------------
    | Deposit tidak menaikkan VIP.
    | Deposit tidak memberi komisi referral.
    | Komisi referral hanya dari pembelian produk BASIC.
    |
    | Logikanya dipindah ke DepositCreditService supaya jalur webhook BayarPro,
    | listener QRIS, dan aksi manual admin memakai aturan yang sama persis.
    */
    private function processPaidDeposit(Deposit $deposit): void
    {
        app(\App\Services\DepositCreditService::class)->credit($deposit);
    }
}
