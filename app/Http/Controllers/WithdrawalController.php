<?php

namespace App\Http\Controllers;

use App\Models\Withdrawal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WithdrawalController extends Controller
{
  private const MIN_WITHDRAW = 50000;

  public function index(Request $request)
  {
    $rows = $request->user()->withdrawals()
      ->with('payoutAccount')
      ->latest()
      ->paginate(20);

    return response()->json($rows);
  }

  public function show(Request $request, $id)
  {
    $row = $request->user()->withdrawals()
      ->with('payoutAccount')
      ->where('id', $id)
      ->firstOrFail();

    return response()->json(['data' => $row]);
  }

  public function store(Request $request)
  {
    $data = $request->validate([
      'amount' => ['required','integer','min:' . self::MIN_WITHDRAW],
      'user_payout_account_id' => ['required','integer'],
    ]);

    $user = $request->user();

    // pastikan payout account milik user
    $account = $user->payoutAccounts()->where('id', $data['user_payout_account_id'])->firstOrFail();

    $amount = (int)$data['amount'];
    $fee = 0;
    $net = $amount - $fee;

    $withdrawal = DB::transaction(function () use ($user, $account, $amount, $fee, $net) {
      // lock user row untuk hindari race condition
      $lockedUser = $user->newQuery()->where('id', $user->id)->lockForUpdate()->first();

      if ((int)$lockedUser->saldo < $amount) {
        abort(422, 'Saldo tidak cukup untuk withdraw.');
      }

      // move to hold
      $lockedUser->saldo = (int)$lockedUser->saldo - $amount;
      $lockedUser->saldo_hold = (int)$lockedUser->saldo_hold + $amount;
      $lockedUser->save();

      return Withdrawal::create([
        'user_id' => $lockedUser->id,
        'user_payout_account_id' => $account->id,
        'amount' => $amount,
        'fee' => $fee,
        'net_amount' => $net,
        'status' => 'PENDING',
      ]);
    });

    return response()->json(['message' => 'Withdraw request dibuat', 'data' => $withdrawal], 201);
  }

  public function cancel(Request $request, $id)
  {
    $user = $request->user();

    $withdrawal = $user->withdrawals()->where('id', $id)->firstOrFail();

    if ($withdrawal->status !== 'PENDING') {
      return response()->json(['message' => 'Hanya request PENDING yang bisa dibatalkan.'], 422);
    }

    DB::transaction(function () use ($user, $withdrawal) {
      $lockedUser = $user->newQuery()->where('id', $user->id)->lockForUpdate()->first();

      // release hold back to saldo
      $lockedUser->saldo = (int)$lockedUser->saldo + (int)$withdrawal->amount;
      $lockedUser->saldo_hold = (int)$lockedUser->saldo_hold - (int)$withdrawal->amount;
      $lockedUser->save();

      $withdrawal->update(['status' => 'CANCELLED']);
    });

    return response()->json(['message' => 'Withdraw request dibatalkan']);
  }
}
