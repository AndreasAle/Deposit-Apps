<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\User;
use App\Models\UserInvestment;
use App\Models\VipRule;
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

            /*
            |--------------------------------------------------------------------------
            | Cek VIP produk
            |--------------------------------------------------------------------------
            | Produk kategori "Semua" sebaiknya min_vip_level = 0.
            | Produk lain tinggal atur min_vip_level sesuai tier.
            */
            if ((int) ($user->vip_level ?? 0) < (int) ($product->min_vip_level ?? 0)) {
                DB::rollBack();

                return back()->with(
                    'error',
                    "VIP kamu belum cukup. Minimal VIP {$product->min_vip_level}"
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Cek saldo
            |--------------------------------------------------------------------------
            */
            if ((float) ($user->saldo ?? 0) < (float) ($product->price ?? 0)) {
                DB::rollBack();

                return back()->with('error', 'Saldo tidak cukup');
            }

            /*
            |--------------------------------------------------------------------------
            | Rule pembelian produk
            |--------------------------------------------------------------------------
            | - Produk duration_days = 1 hanya boleh dibeli 1x per hari.
            | - Produk duration_days selain 1 boleh dibeli berkali-kali selama saldo cukup.
            */
            if ((int) $product->duration_days === 1) {
                $alreadyBoughtToday = UserInvestment::where('user_id', $user->id)
                    ->where('product_id', $product->id)
                    ->whereDate('created_at', today())
                    ->exists();

                if ($alreadyBoughtToday) {
                    DB::rollBack();

                    return back()->with('error', 'Produk harian ini hanya bisa dibeli 1 kali per hari.');
                }
            }

            /*
            |--------------------------------------------------------------------------
            | Potong saldo utama
            |--------------------------------------------------------------------------
            */
            $user->saldo = (float) $user->saldo - (float) $product->price;
            $user->save();

            /*
            |--------------------------------------------------------------------------
            | Buat investasi user
            |--------------------------------------------------------------------------
            */
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

            /*
            |--------------------------------------------------------------------------
            | Sync VIP berdasarkan total pembelian produk
            |--------------------------------------------------------------------------
            | Deposit tidak dihitung.
            */
            $this->syncUserVipByInvestment($user);

            /*
            |--------------------------------------------------------------------------
            | Referral commission buy 33%
            |--------------------------------------------------------------------------
            */
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

    private function syncUserVipByInvestment(User $user): void
    {
        /*
        |--------------------------------------------------------------------------
        | Total pembelian produk user
        |--------------------------------------------------------------------------
        | Ini akumulasi semua investasi yang pernah dibeli user.
        | Kalau nanti ada status cancelled/refund, tinggal exclude di query ini.
        */
        $totalInvestment = UserInvestment::where('user_id', $user->id)
            ->sum('price');

        $vipRules = VipRule::where('is_active', 1)
            ->orderBy('min_total_deposit', 'asc')
            ->get();

        $newVip = (int) ($user->vip_level ?? 0);

        foreach ($vipRules as $rule) {
            if ((float) $totalInvestment >= (float) $rule->min_total_deposit) {
                $newVip = (int) $rule->vip_level;
            }
        }

        if ($newVip > (int) ($user->vip_level ?? 0)) {
            $user->vip_level = $newVip;
            $user->save();
        }
    }
}