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
    /*
    |--------------------------------------------------------------------------
    | Product Category Rules
    |--------------------------------------------------------------------------
    | Sesuai request client terbaru:
    |
    | category_id = 1 / Semua / All Asset (BASIC)
    | - Bisa dibeli berkali-kali
    | - Bisa dibeli berkali-kali dalam 1 hari
    | - MASUK profit, dikunci sampai tanggal selesai
    | - Dapat referral
    |
    | Catatan perubahan: dulu kategori ini sengaja dibuat tanpa profit dan
    | langsung berstatus selesai, sehingga di layar user tampak "Durasi 0 Hari,
    | profit Rp 0, FINISHED" tepat setelah dibeli - itu yang banyak
    | dikeluhkan. Sekarang perlakuannya sama seperti kategori lain: berjalan
    | selama durasi produknya, profitnya masuk ke saldo penarikan saat selesai.
    |
    | category_id = 2 / Saham Capital Wave
    | - Masuk profit harian
    | - Hanya bisa dibeli 1 kali per produk
    | - Tidak dapat referral
    |
    | category_id = 3 / Capital Wave Pro
    | - Masuk profit harian
    | - Hanya bisa dibeli 1 kali per produk
    | - Tidak dapat referral
    */

    private const BASIC_CATEGORY_IDS = [1];

    /*
    | Semua kategori kini menghasilkan profit. Yang membedakannya tinggal
    | aturan pembelian (berkali-kali vs sekali) dan referral.
    */
    private const PROFIT_CATEGORY_IDS = [1, 2, 3];

    private const VIP_CATEGORY_IDS = [2, 3]; // Untuk rule 1 kali beli per produk

    private const REFERRAL_ALLOWED_CATEGORY_IDS = [1];

    public function buy($id)
    {
        $authUser = auth()->user();

        if (!$authUser) {
            return redirect('/login');
        }

        $product = Product::query()
            ->where('id', $id)
            ->where('is_active', 1)
            ->firstOrFail();

        DB::beginTransaction();

        try {
            /*
            |--------------------------------------------------------------------------
            | Lock user
            |--------------------------------------------------------------------------
            | Aman dari double click / race condition saldo.
            */
            $user = User::query()
                ->where('id', $authUser->id)
                ->lockForUpdate()
                ->firstOrFail();

            /*
            |--------------------------------------------------------------------------
            | Cek VIP produk
            |--------------------------------------------------------------------------
            | Kalau produk butuh VIP tertentu, user wajib sudah mencapai VIP itu.
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
            | Cek saldo utama
            |--------------------------------------------------------------------------
            */
            if ((float) ($user->saldo ?? 0) < (float) ($product->price ?? 0)) {
                DB::rollBack();

                return back()->with('error', 'Saldo tidak cukup');
            }

            /*
            |--------------------------------------------------------------------------
            | Rule produk VIP: hanya bisa dibeli 1 kali per produk
            |--------------------------------------------------------------------------
            | Saham Capital Wave / Capital Wave Pro:
            | - Kalau user pernah beli produk ini, status apapun, tidak boleh beli lagi.
            | - Jadi walaupun sudah completed, tetap tidak bisa beli produk yang sama.
            |
            | Produk Semua / All Asset:
            | - Tidak kena rule ini.
            | - Bisa dibeli berkali-kali.
            */
            if ($this->isVipProduct($product)) {
                $alreadyBoughtThisProduct = UserInvestment::query()
                    ->where('user_id', $user->id)
                    ->where('product_id', $product->id)
                    ->exists();

                if ($alreadyBoughtThisProduct) {
                    DB::rollBack();

                    return back()->with(
                        'error',
                        'Produk ini hanya bisa dibeli 1 kali. Silakan naik VIP untuk membeli produk selanjutnya.'
                    );
                }
            }

            /*
            |--------------------------------------------------------------------------
            | Rule lama duration_days = 1 DIHAPUS untuk kategori Semua
            |--------------------------------------------------------------------------
            | Sesuai request:
            | Produk kategori Semua boleh dibeli berkali-kali dalam 1 hari asal saldo cukup.
            */

            /*
            |--------------------------------------------------------------------------
            | Potong saldo utama
            |--------------------------------------------------------------------------
            */
            $user->saldo = (float) ($user->saldo ?? 0) - (float) ($product->price ?? 0);
            $user->save();

            /*
            |--------------------------------------------------------------------------
            | Buat investasi user
            |--------------------------------------------------------------------------
            | Semua kategori masuk profit. Profitnya TIDAK cair saat dibeli:
            | dikunci sampai end_date, lalu dimasukkan ke saldo penarikan oleh
            | perintah investments:settle-profits.
            */
            $isProfitProduct = $this->isProfitProduct($product);

            /*
            | Durasi diambil apa adanya dari produk. Minimal 1 hari supaya
            | investasi tidak pernah lahir dalam keadaan sudah selesai -
            | itu yang dulu membuat produk BASIC tampak langsung FINISHED
            | dengan profit nol begitu dibeli.
            */
            $durasi = max((int) ($product->duration_days ?? 0), 1);

            $investment = UserInvestment::create([
                'user_id'       => $user->id,
                'product_id'    => $product->id,
                'price'         => (int) ($product->price ?? 0),

                'daily_profit'  => $isProfitProduct ? (int) ($product->daily_profit ?? 0) : 0,
                'duration_days' => $isProfitProduct ? $durasi : 0,
                'total_profit'  => $isProfitProduct ? (int) ($product->total_profit ?? 0) : 0,

                'start_date'    => now(),
                'end_date'      => $isProfitProduct
                    ? now()->addDays($durasi)
                    : now(),

                /*
                |--------------------------------------------------------------------------
                | Status investasi
                |--------------------------------------------------------------------------
                | Produk profit tetap active.
                | Produk non-profit dibuat completed supaya tidak ikut diproses cron profit.
                |
                | Jangan pakai 'non_profit' kalau kolom status kamu enum dan belum mendukung value itu.
                */
                'status'        => $isProfitProduct ? 'active' : 'finished',
            ]);

            /*
            |--------------------------------------------------------------------------
            | Sync VIP berdasarkan total pembelian produk
            |--------------------------------------------------------------------------
            | Deposit tidak dihitung.
            | Produk Semua, Saham Capital Wave, dan Capital Wave Pro tetap dihitung ke akumulasi VIP.
            */
            $this->syncUserVipByInvestment($user);

            /*
            |--------------------------------------------------------------------------
            | Referral 3 Level
            |--------------------------------------------------------------------------
            | Produk Semua / All Asset:
            | - Level 1
            | - Level 2
            | - Level 3
            |
            | Saham Capital Wave / Capital Wave Pro:
            | - Tidak dapat referral
            */
            if ($this->isReferralAllowedProduct($product)) {
                app(ReferralService::class)->give(
                    $user,
                    'basic_product_buy',
                    (int) $investment->id,
                    (float) $investment->price
                );
            }

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
        | Semua pembelian produk dihitung:
        | - Semua / All Asset
        | - Saham Capital Wave
        | - Capital Wave Pro
        |
        | Deposit tidak dihitung.
        */
        $totalInvestment = UserInvestment::query()
            ->where('user_id', $user->id)
            ->sum('price');

        /*
        |--------------------------------------------------------------------------
        | VIP Rules
        |--------------------------------------------------------------------------
        | Di database kamu field-nya masih min_total_deposit.
        | Walaupun namanya deposit, kita pakai sebagai batas total pembelian produk.
        */
        $vipRules = VipRule::query()
            ->where('is_active', 1)
            ->orderBy('min_total_deposit', 'asc')
            ->get();

        $newVip = (int) ($user->vip_level ?? 0);

        foreach ($vipRules as $rule) {
            if ((float) $totalInvestment >= (float) $rule->min_total_deposit) {
                $newVip = (int) $rule->vip_level;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | VIP hanya naik otomatis
        |--------------------------------------------------------------------------
        | Tidak diturunkan otomatis agar aman.
        */
        if ($newVip > (int) ($user->vip_level ?? 0)) {
            $user->vip_level = $newVip;
            $user->save();
        }
    }

    private function isBasicProduct(Product $product): bool
    {
        return in_array(
            (int) ($product->category_id ?? 0),
            self::BASIC_CATEGORY_IDS,
            true
        );
    }

    private function isProfitProduct(Product $product): bool
    {
        return in_array(
            (int) ($product->category_id ?? 0),
            self::PROFIT_CATEGORY_IDS,
            true
        );
    }

    private function isVipProduct(Product $product): bool
    {
        return in_array(
            (int) ($product->category_id ?? 0),
            self::VIP_CATEGORY_IDS,
            true
        );
    }

    private function isReferralAllowedProduct(Product $product): bool
    {
        return in_array(
            (int) ($product->category_id ?? 0),
            self::REFERRAL_ALLOWED_CATEGORY_IDS,
            true
        );
    }
}