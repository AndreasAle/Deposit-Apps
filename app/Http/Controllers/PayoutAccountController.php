<?php

namespace App\Http\Controllers;

use App\Models\UserPayoutAccount;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PayoutAccountController extends Controller
{
  public function index(Request $request)
  {
    $accounts = $request->user()->payoutAccounts()->latest()->get();
    return response()->json(['data' => $accounts]);
  }

  public function store(Request $request)
  {
    $data = $request->validate([
      'type' => ['required', Rule::in(['BANK','EWALLET'])],
      'provider' => ['required', 'string', 'max:50', Rule::in($this->allowedProviders())],
      'account_name' => ['required','string','max:100'],
      'account_number' => ['required','string','max:50'],
      'is_default' => ['nullable','boolean'],
    ]);

    $user = $request->user();

    if (!empty($data['is_default'])) {
      $user->payoutAccounts()->update(['is_default' => false]);
    }

    $account = UserPayoutAccount::create([
      'user_id' => $user->id,
      ...$data,
      'is_default' => (bool)($data['is_default'] ?? false),
    ]);

    return response()->json(['message' => 'Payout account created', 'data' => $account], 201);
  }

  public function update(Request $request, $id)
  {
    $user = $request->user();
    $account = $user->payoutAccounts()->where('id', $id)->firstOrFail();

    $data = $request->validate([
      'type' => ['sometimes', Rule::in(['BANK','EWALLET'])],
      'provider' => ['sometimes', 'string', 'max:50', Rule::in($this->allowedProviders())],
      'account_name' => ['sometimes','string','max:100'],
      'account_number' => ['sometimes','string','max:50'],
      'is_default' => ['nullable','boolean'],
    ]);

    if (!empty($data['is_default'])) {
      $user->payoutAccounts()->update(['is_default' => false]);
    }

    $account->update($data);

    return response()->json(['message' => 'Payout account updated', 'data' => $account]);
  }

  public function destroy(Request $request, $id)
  {
    $user = $request->user();
    $account = $user->payoutAccounts()->where('id', $id)->firstOrFail();

    $account->delete();

    return response()->json(['message' => 'Payout account deleted']);
  }

  private function allowedProviders(): array
  {
    return [
      'BCA',
      'BRI',
      'BNI',
      'MANDIRI',
      'PERMATA',
      'CIMB',
      'BSI',
      'DANA',
      'OVO',
      'GOPAY',
      'SHOPEEPAY',
      'LINKAJA',
    ];
  }
}