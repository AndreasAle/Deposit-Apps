<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\UserInvestment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\ReferralService;
use App\Models\User;
class ProductBuyController extends Controller
{
public function buy($id)
{
    $authUser = auth()->user();
    if (!$authUser) return redirect('/login');

    $product = Product::where('id', $id)->where('is_active', 1)->firstOrFail();

    DB::beginTransaction();
    try {
        // 🔒 lock user biar saldo aman
        $user = User::lockForUpdate()->findOrFail($authUser->id);

        if ($user->vip_level < $product->min_vip_level) {
            DB::rollBack();
            return back()->with('error', "VIP kamu belum cukup. Minimal VIP {$product->min_vip_level}");
        }

        if ($user->saldo < $product->price) {
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

        $user->saldo -= $product->price;
        $user->save();

        $inv = UserInvestment::create([
            'user_id'        => $user->id,
            'product_id'     => $product->id,
            'price'          => $product->price,
            'daily_profit'   => $product->daily_profit,
            'duration_days'  => $product->duration_days,
            'total_profit'   => $product->total_profit,
            'start_date'     => now(),
            'end_date'       => now()->addDays($product->duration_days),
            'status'         => 'active'
        ]);

        // ✅ REFERRAL COMMISSION: buy 3%
        (new ReferralService())->give(
            $user,
            'buy',
            (int) $inv->id,
            (float) $inv->price,
            0.03
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
