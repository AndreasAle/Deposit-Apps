<?php

namespace App\Http\Controllers;

use App\Models\Deposit;
use App\Models\User;
use App\Services\BankPay\BankPayDepositService;
use App\Services\DepositChannels;
use App\Services\DepositQrisService;
use App\Services\DepositSettlementService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

/**
 * Deposit berjalan di atas dua saluran yang bisa dipilih user (lihat
 * config/deposit.php):
 *
 *   bankpay      Payment gateway. Invoice dibuat lewat API, pembayaran
 *                dikonfirmasi OTOMATIS oleh notifikasi server gateway.
 *   qris_statis  QRIS statis milik sendiri + nominal unik, tanpa gateway.
 *                Konfirmasi lewat notification listener di HP
 *                (ListenerController + MutationMatcher).
 *
 * Saluran yang dipakai disimpan per deposit di kolom `payment_channel`,
 * karena itulah yang menentukan bagaimana sebuah invoice boleh dilunasi.
 */
class DepositController extends Controller
{
    public function index()
    {
        $user = User::findOrFail(Auth::id());

        $deposits = Deposit::where('user_id', $user->id)
            ->latest()
            ->get();

        $channels = DepositChannels::enabled();

        return view('deposit.index', compact('deposits', 'user', 'channels'));
    }

    public function history()
    {
        $user = User::findOrFail(Auth::id());

        $deposits = Deposit::where('user_id', $user->id)
            ->latest()
            ->get();

        return view('deposit.history', compact('deposits', 'user'));
    }

    public function store(Request $request, BankPayDepositService $bankPay)
    {
        $request->validate([
            'amount' => 'required|integer|min:50000|max:10000000',
            'payment_channel' => 'nullable|string|max:32',
            'method' => 'nullable|string|max:32',
            'selected_channel' => 'nullable|string|max:32',
        ]);

        $user = User::findOrFail(Auth::id());

        $amount = (int) $request->amount;

        // Pilihan user divalidasi ulang di sini: saluran yang dimatikan lewat
        // .env tidak boleh bisa dipakai hanya karena form-nya dipalsukan.
        $channel = DepositChannels::resolve($request->input('payment_channel'));

        if ($channel === null) {
            return back()->with('error', 'Deposit sedang tidak tersedia. Coba lagi nanti.');
        }

        // Metode pembayaran (QRIS/DANA) hanya label tampilan; yang menentukan
        // alur teknis adalah $channel di atas.
        $method = strtoupper($request->selected_channel ?: $request->method ?: 'QRIS');

        // Wajib unik dan 16-32 karakter (ketentuan gateway). Ini 23 karakter.
        $orderId = 'DEP' . now()->format('YmdHis') . strtoupper(substr(md5($user->id . microtime(true)), 0, 6));

        if ($channel === DepositChannels::QRIS_STATIS) {
            return $this->storeViaQrisStatis($user, $amount, $method, $orderId);
        }

        return $this->storeViaBankPay($user, $amount, $method, $orderId, $bankPay);
    }

    /**
     * Alur gateway: buat invoice di BankPay, simpan tautan bayar + isi QR-nya.
     *
     * Baris deposit sengaja dibuat lebih dulu supaya panggilan HTTP ke gateway
     * tidak menahan transaksi database, dan supaya percobaan yang gagal tetap
     * meninggalkan jejak (status FAILED) untuk ditelusuri.
     */
    private function storeViaBankPay(
        User $user,
        int $amount,
        string $method,
        string $orderId,
        BankPayDepositService $bankPay
    ) {
        $deposit = Deposit::create([
            'user_id' => $user->id,
            'order_id' => $orderId,
            'amount' => $amount,
            'method' => $method,
            'selected_channel' => $method,
            'payment_channel' => DepositChannels::BANKPAY,
            'status' => 'UNPAID',
            'pay_fee' => 0,
            // Yang dibayar user = nominal penuh; biaya gateway ditanggung merchant.
            'real_amount' => $amount,
            'expired_at' => now()->addMinutes((int) config('services.bankpay.expiry_minutes', 60)),
        ]);

        try {
            $result = $bankPay->createInvoice($deposit);
        } catch (\Throwable $e) {
            $deposit->status = 'FAILED';
            $deposit->gateway_response = ['error' => $e->getMessage()];
            $deposit->save();

            Log::error('Deposit BankPay gagal dibuat', [
                'deposit_id' => $deposit->id,
                'order_id' => $orderId,
                'message' => $e->getMessage(),
            ]);

            return back()->with('error', 'Gagal membuat pembayaran: ' . $e->getMessage());
        }

        $deposit->pay_url = $result['pay_url'];
        // Kalau gateway mengirim isi QR, QR dirender lokal di halaman invoice
        // sehingga user menyelesaikan pembayaran tanpa keluar dari aplikasi.
        $deposit->pay_data = $result['qr_content'];
        $deposit->gateway_response = $result['response'];
        $deposit->save();

        return redirect()
            ->route('deposit.invoice', $deposit->id)
            ->with('success', 'Invoice deposit berhasil dibuat');
    }

    /**
     * Alur tanpa gateway: QRIS statis milik sendiri dibuat dinamis dengan
     * nominal unik per deposit, lalu dikonfirmasi oleh notification listener
     * (lihat ListenerController + MutationMatcher).
     */
    private function storeViaQrisStatis(User $user, int $amount, string $method, string $orderId)
    {
        try {
            $deposit = app(DepositQrisService::class)
                ->createInvoice($user, $amount, $method, $orderId);
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

    public function invoice($id, BankPayDepositService $bankPay)
    {
        $user = User::findOrFail(Auth::id());

        $deposit = Deposit::where('user_id', $user->id)
            ->where('id', $id)
            ->firstOrFail();

        // Cadangan anti notifikasi-gagal: tanya status langsung ke gateway.
        // Kalau ternyata sudah dibayar tapi belum tercatat PAID, lunasi
        // sekarang. Aman dobel karena penyelesaiannya dikunci + idempoten.
        //
        // Halaman ini memuat ulang dirinya secara berkala selama menunggu
        // pembayaran, jadi tanpa penjagaan kelayakan di bawah satu tab yang
        // ditinggalkan terbuka akan menanyai gateway tanpa henti.
        if ($deposit->masihBisaDitanyakanKeGateway()) {
            $this->syncBankPayStatus($deposit, $bankPay);
            $deposit->refresh();
        }

        $displayPayUrl = $deposit->pay_url ?: null;
        $qrImageSrc = null;

        if ($deposit->status !== 'PAID' && !empty($deposit->pay_data)) {
            try {
                $qrSvg = QrCode::format('svg')
                    ->size(520)
                    ->margin(1)
                    ->generate($deposit->pay_data);

                $qrImageSrc = 'data:image/svg+xml;base64,' . base64_encode($qrSvg);
            } catch (\Throwable $e) {
                Log::error('Gagal generate QR deposit', [
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
     * Sinkronkan status deposit langsung dari BankPay (polling).
     */
    private function syncBankPayStatus(Deposit $deposit, BankPayDepositService $bankPay): void
    {
        try {
            $result = $bankPay->queryOrder($deposit->order_id);

            if (empty($result['success']) || empty($result['paid'])) {
                return;
            }

            app(DepositSettlementService::class)->settle(
                $deposit->id,
                $result['amount'],
                $result['transaction_id'],
                $result['response'],
                'status-sync'
            );
        } catch (\Throwable $e) {
            Log::error('BankPay status-sync error', [
                'deposit_id' => $deposit->id,
                'message' => $e->getMessage(),
            ]);
        }
    }
}
