<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\User;
use App\Models\UserInvestment;
use App\Services\ReferralService;
use Illuminate\Support\Facades\DB;

class ProductBuyController extends Controller
{
    public function buy($id)
    {
        $authUser = auth()->user();

        if (!$authUser) {
            return redirect('/login');
        }

        $product = Product::where('id', $id)
            ->where('is_active', 1)
            ->firstOrFail();

        DB::beginTransaction();

        try {
            // Lock user biar saldo aman dari double click / race condition
            $user = User::where('id', $authUser->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ((int) ($user->vip_level ?? 0) < (int) ($product->min_vip_level ?? 0)) {
                DB::rollBack();

                return back()->with(
                    'error',
                    "VIP kamu belum cukup. Minimal VIP {$product->min_vip_level}"
                );
            }

            if ((float) ($user->saldo ?? 0) < (float) ($product->price ?? 0)) {
                DB::rollBack();

                return back()->with('error', 'Saldo tidak cukup');
            }

            $alreadyActive = UserInvestment::where('user_id', $user->id)
                ->where('product_id', $product->id)
                ->where('status', 'active')
                ->exists();

            if ($alreadyActive) {
                DB::rollBack();

                return back()->with('error', 'Produk ini masih aktif');
            }

            $user->saldo = (float) $user->saldo - (float) $product->price;
            $user->save();

            $inv = UserInvestment::create([
                'user_id'       => $user->id,
                'product_id'    => $product->id,
                'price'         => (int) $product->price,
                'daily_profit'  => (int) $product->daily_profit,
                'duration_days' => (int) $product->duration_days,
                'total_profit'  => (int) $product->total_profit,
                'start_date'    => now(),
                'end_date'      => now()->addDays((int) $product->duration_days),
                'status'        => 'active',
            ]);

            // Referral commission buy 33%
            (new ReferralService())->give(
                $user,
                'buy',
                (int) $inv->id,
                (float) $inv->price,
                0.33
            );

            DB::commit();

            return redirect('/investasi')->with('success', 'Produk berhasil dibeli');
        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);

            return back()->with('error', 'Terjadi kesalahan sistem');
        }
    }
}