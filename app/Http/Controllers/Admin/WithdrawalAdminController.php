<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Withdrawal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class WithdrawalAdminController extends Controller
{
  public function index(Request $request)
  {
    $status = $request->query('status');

    $q = Withdrawal::query()->with(['user','payoutAccount'])->latest();

    if ($status) {
      $q->where('status', $status);
    }

    return response()->json($q->paginate(30));
  }

  public function approve(Request $request, $id)
  {
    $admin = $request->user();

    $row = Withdrawal::where('id', $id)->firstOrFail();

    if ($row->status !== 'PENDING') {
      return response()->json(['message' => 'Status harus PENDING untuk approve.'], 422);
    }

    $row->update([
      'status' => 'APPROVED',
      'admin_id' => $admin->id,
      'approved_at' => now(),
    ]);

    return response()->json(['message' => 'Withdraw approved', 'data' => $row]);
  }

  public function reject(Request $request, $id)
  {
    $data = $request->validate([
      'reason' => ['required','string','max:500'],
    ]);

    $admin = $request->user();

    DB::transaction(function () use ($id, $data, $admin) {
      $row = Withdrawal::lockForUpdate()->where('id', $id)->firstOrFail();

      if (!in_array($row->status, ['PENDING','APPROVED'], true)) {
        abort(422, 'Status harus PENDING/APPROVED untuk reject.');
      }

      $user = $row->user()->lockForUpdate()->first();

      // return funds
      $user->saldo = (int)$user->saldo + (int)$row->amount;
      $user->saldo_hold = (int)$user->saldo_hold - (int)$row->amount;
      $user->save();

      $row->update([
        'status' => 'REJECTED',
        'admin_id' => $admin->id,
        'reject_reason' => $data['reason'],
      ]);
    });

    return response()->json(['message' => 'Withdraw rejected']);
  }

  public function markPaid(Request $request, $id)
  {
    $admin = $request->user();

    $data = $request->validate([
      'proof' => ['nullable','file','mimes:jpg,jpeg,png,pdf','max:2048'],
    ]);

    DB::transaction(function () use ($request, $id, $admin, $data) {
      $row = Withdrawal::lockForUpdate()->where('id', $id)->firstOrFail();

      if (!in_array($row->status, ['APPROVED','PENDING'], true)) {
        abort(422, 'Status harus APPROVED/PENDING untuk mark paid.');
      }

      $user = $row->user()->lockForUpdate()->first();

      // finalize: reduce hold permanently
      $user->saldo_hold = (int)$user->saldo_hold - (int)$row->amount;
      $user->save();

      $proofUrl = $row->proof_url;

      if ($request->hasFile('proof')) {
        $path = $request->file('proof')->store('withdraw_proofs', 'public');
        $proofUrl = Storage::disk('public')->url($path);
      }

      $row->update([
        'status' => 'PAID',
        'admin_id' => $admin->id,
        'paid_at' => now(),
        'proof_url' => $proofUrl,
      ]);
    });

    return response()->json(['message' => 'Withdraw marked as paid']);
  }
}
