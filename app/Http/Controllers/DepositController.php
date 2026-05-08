<?php

namespace App\Http\Controllers;

use App\Models\Deposit;
use App\Models\User;
use App\Models\VipRule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Services\ReferralService;
use App\Services\JayaPayService;
use Illuminate\Support\Facades\Http;
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

    public function store(Request $request, JayaPayService $jayaPay)
    {
$request->validate([
    'amount' => 'required|integer|min:50000|max:10000000',
    'method' => 'nullable|string|max:32',
    'selected_channel' => 'nullable|string|max:32',
]);
        $user = User::findOrFail(Auth::id());

        $amount = (int) $request->amount;
        $method = $request->method ?: 'QRIS';
        $selectedChannel = $request->selected_channel ?: $method;

        $orderId = 'DEP' . now()->format('YmdHis') . strtoupper(substr(md5($user->id . microtime(true)), 0, 6));

        DB::beginTransaction();

        try {
       $deposit = Deposit::create([
    'user_id' => $user->id,
    'order_id' => $orderId,
    'amount' => $amount,
    'method' => $method,
    'selected_channel' => $selectedChannel,
    'status' => 'UNPAID',
]);
    $result = $jayaPay->createDepositOrder([
    'order_num' => $orderId,
    'amount' => $amount,
    'method' => $method,
    'product_detail' => 'Deposit Saldo #' . $orderId,
    'name' => $user->name ?: 'Customer',
    'email' => $user->email ?: 'customer@email.com',
    'phone' => $user->phone ?? $user->no_hp ?? '080000000000',
]);

            $response = $result['response'] ?? [];

            $deposit->gateway_response = $result;

            if (!$result['success']) {
                $deposit->status = 'FAILED';
                $deposit->save();

                DB::commit();

                return back()->with('error', $result['message'] ?? 'Gagal membuat pembayaran JayaPay');
            }

            $payData = $response['payData'] ?? null;
            $decodedPayData = null;

            if ($payData) {
                $decodedPayData = is_string($payData) ? json_decode($payData, true) : $payData;
            }

            $realAmount = $amount;

            if (is_array($decodedPayData)) {
                if (!empty($decodedPayData['realMoney'])) {
                    $realAmount = (int) $decodedPayData['realMoney'];
                } elseif (!empty($decodedPayData['matchingId'])) {
                    $realAmount = (int) $decodedPayData['matchingId'];
                }
            }

            $deposit->plat_order_num = $response['platOrderNum'] ?? null;
            $deposit->pay_url = $response['url'] ?? null;
            $deposit->pay_data = $payData ? (is_string($payData) ? $payData : json_encode($payData)) : null;
            $deposit->pay_fee = isset($response['payFee']) ? (float) $response['payFee'] : 0;
            $deposit->real_amount = $realAmount;
            $deposit->expired_at = now()->addMinutes((int) config('services.jayapay.expiry_period', 1440));
            $deposit->save();

DB::commit();

return redirect()
    ->route('deposit.invoice', $deposit->id)
    ->with('success', 'Invoice deposit berhasil dibuat');

        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('Deposit JayaPay store error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return back()->with('error', 'Terjadi kesalahan saat membuat deposit');
        }
    }
public function invoice($id)
{
    $user = User::findOrFail(Auth::id());

    $deposit = Deposit::where('user_id', $user->id)
        ->where('id', $id)
        ->firstOrFail();

    $displayPayUrl = !empty($deposit->pay_url)
        ? $this->buildJayaPayDisplayUrl($deposit)
        : null;

    $qrImageSrc = null;

    if ($deposit->status !== 'PAID' && !empty($displayPayUrl)) {
        try {
            $v2Url = $this->buildJayaPayV2Url($displayPayUrl);
            $v2Payload = $this->buildJayaPayV2Payload($displayPayUrl);

            $origin = parse_url($displayPayUrl, PHP_URL_SCHEME) . '://' . parse_url($displayPayUrl, PHP_URL_HOST);

            // Coba mode JSON
            $v2Response = Http::timeout(20)
                ->asJson()
                ->withHeaders([
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 Chrome/120 Safari/537.36',
                    'Accept' => 'application/json, text/plain, */*',
                    'Origin' => $origin,
                    'Referer' => $displayPayUrl,
                ])
                ->post($v2Url, $v2Payload);

            $json = $v2Response->json();
            $vaNumber = data_get($json, 'data.vaNumber');

            // Kalau JSON gagal, coba mode form
            if (empty($vaNumber)) {
                $v2ResponseForm = Http::timeout(20)
                    ->asForm()
                    ->withHeaders([
                        'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 Chrome/120 Safari/537.36',
                        'Accept' => 'application/json, text/plain, */*',
                        'Origin' => $origin,
                        'Referer' => $displayPayUrl,
                    ])
                    ->post($v2Url, $v2Payload);

                $jsonForm = $v2ResponseForm->json();
                $vaNumber = data_get($jsonForm, 'data.vaNumber');

                Log::info('JayaPay V2 FORM response', [
                    'deposit_id' => $deposit->id,
                    'status' => $v2ResponseForm->status(),
                    'body' => $v2ResponseForm->body(),
                ]);
            }

            Log::info('JayaPay V2 QR check', [
                'deposit_id' => $deposit->id,
                'v2_url' => $v2Url,
                'payload' => $v2Payload,
                'status' => $v2Response->status(),
                'has_va_number' => !empty($vaNumber),
                'body' => $v2Response->body(),
            ]);

            if (!empty($vaNumber)) {
                $qrSvg = QrCode::format('svg')
                    ->size(520)
                    ->margin(1)
                    ->generate($vaNumber);

                $qrImageSrc = 'data:image/svg+xml;base64,' . base64_encode($qrSvg);
            }
        } catch (\Throwable $e) {
            Log::error('Gagal generate QR JayaPay', [
                'deposit_id' => $deposit->id,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
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

private function buildJayaPayDisplayUrl(Deposit $deposit): string
{
    $comboChannels = [
        'DANA',
        'GOPAY',
        'OVO',
        'DOKU',
        'LINKAJA',
        'SHOPEEPAY',
        'BCA',
        'MANDIRI',
    ];

    $selectedChannel = strtoupper($deposit->selected_channel ?: $deposit->method);

    if (!in_array($selectedChannel, $comboChannels, true)) {
        return $deposit->pay_url;
    }

    $parts = parse_url($deposit->pay_url);

    if (!$parts || empty($parts['scheme']) || empty($parts['host'])) {
        return $deposit->pay_url;
    }

    $query = [];
    parse_str($parts['query'] ?? '', $query);

    $query['logState'] = 2;
    $query['method'] = 'QRIS';
    $query['secondMethod'] = $selectedChannel;

    $base = $parts['scheme'] . '://' . $parts['host'] . '/cash/PACKQRIS';

    return $base . '?' . http_build_query($query);
}

private function buildJayaPayV2Url(string $displayPayUrl): string
{
    $parts = parse_url($displayPayUrl);

    if (!$parts || empty($parts['scheme']) || empty($parts['host'])) {
        return $displayPayUrl;
    }

    return $parts['scheme'] . '://' . $parts['host'] . '/gateway/order/detail/v2';
}

private function buildJayaPayV2Payload(string $displayPayUrl): array
{
    $parts = parse_url($displayPayUrl);

    $query = [];
    parse_str($parts['query'] ?? '', $query);

    return [
        'logState' => $query['logState'] ?? '2',
        'orderNum' => $query['orderNum'] ?? null,
        'method' => $query['method'] ?? 'QRIS',
        'secondMethod' => $query['secondMethod'] ?? null,
        'SG' => $query['SG'] ?? null,
        'SV' => $query['SV'] ?? null,
        'SX' => $query['SX'] ?? null,
        'SN' => $query['SN'] ?? null,
    ];
}
    public function callback(Request $request, JayaPayService $jayaPay)
    {
        $payload = $request->all();

        Log::info('JayaPay deposit callback received', $payload);

        try {
            $isValid = $jayaPay->verifyCallback($payload);

            if (!$isValid) {
                Log::warning('JayaPay callback invalid signature', $payload);
                return response('INVALID SIGN', 400);
            }

            if (($payload['status'] ?? null) !== 'SUCCESS') {
                Log::info('JayaPay callback ignored because status is not SUCCESS', $payload);
                return response('SUCCESS', 200);
            }

            $orderId = $payload['orderNum'] ?? null;

            if (!$orderId) {
                return response('ORDER NUM EMPTY', 400);
            }

            DB::beginTransaction();

            $deposit = Deposit::where('order_id', $orderId)
                ->lockForUpdate()
                ->first();

            if (!$deposit) {
                DB::rollBack();
                Log::warning('JayaPay callback deposit not found', $payload);
                return response('ORDER NOT FOUND', 404);
            }

            if ($deposit->status === 'PAID') {
                DB::commit();
                return response('SUCCESS', 200);
            }

            $deposit->status = 'PAID';
            $deposit->plat_order_num = $payload['platOrderNum'] ?? $deposit->plat_order_num;
            $deposit->pay_fee = isset($payload['payFee']) ? (float) $payload['payFee'] : $deposit->pay_fee;
           $deposit->gateway_response = $payload;
            $deposit->paid_at = now();
            $deposit->save();

            $this->processPaidDeposit($deposit);

            DB::commit();

            return response('SUCCESS', 200);

        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('JayaPay deposit callback error', [
                'message' => $e->getMessage(),
                'payload' => $payload,
                'trace' => $e->getTraceAsString(),
            ]);

            return response('ERROR', 500);
        }
    }

    private function processPaidDeposit(Deposit $deposit): void
    {
        $user = User::lockForUpdate()->findOrFail($deposit->user_id);

        $user->saldo = (float) $user->saldo + (float) $deposit->amount;

        $totalDeposit = Deposit::where('user_id', $user->id)
            ->where('status', 'PAID')
            ->sum('amount');

        $vipRules = VipRule::where('is_active', 1)
            ->orderBy('min_total_deposit', 'asc')
            ->get();

        $newVip = $user->vip_level;

        foreach ($vipRules as $rule) {
            if ($totalDeposit >= $rule->min_total_deposit) {
                $newVip = $rule->vip_level;
            }
        }

        if ($newVip > $user->vip_level) {
            $user->vip_level = $newVip;
        }

        $user->save();

        (new ReferralService())->give(
            $user,
            'deposit',
            (int) $deposit->id,
            (float) $deposit->amount,
            0.05
        );
    }








}