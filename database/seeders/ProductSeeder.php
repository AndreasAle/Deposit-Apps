<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    public function run()
    {
        // Ambil kategori ID
        $reguler  = DB::table('product_categories')->where('slug', 'reguler')->first();
        $harian   = DB::table('product_categories')->where('slug', 'harian')->first();
        $premium  = DB::table('product_categories')->where('slug', 'premium')->first();

        DB::table('products')->insert([
            // ======================
            // REGULER
            // ======================
            [
                'category_id'   => $reguler->id,
                'name'          => 'Produk Reguler 1',
                'price'         => 50000,
                'daily_profit'  => 16250,
                'duration_days' => 95,
                'total_profit'  => 16250 * 95,
                'min_vip_level' => 0,
                'is_active'     => 1,
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'category_id'   => $reguler->id,
                'name'          => 'Produk Reguler 2',
                'price'         => 250000,
                'daily_profit'  => 83750,
                'duration_days' => 95,
                'total_profit'  => 83750 * 95,
                'min_vip_level' => 0,
                'is_active'     => 1,
                'created_at'    => now(),
                'updated_at'    => now(),
            ],

            // ======================
            // HARIAN
            // ======================
            [
                'category_id'   => $harian->id,
                'name'          => 'Produk Harian 1',
                'price'         => 50000,
                'daily_profit'  => 75000,
                'duration_days' => 1,
                'total_profit'  => 75000,
                'min_vip_level' => 1,
                'is_active'     => 1,
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'category_id'   => $harian->id,
                'name'          => 'Produk Harian 2',
                'price'         => 250000,
                'daily_profit'  => 125000,
                'duration_days' => 3,
                'total_profit'  => 125000 * 3,
                'min_vip_level' => 2,
                'is_active'     => 1,
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'category_id'   => $harian->id,
                'name'          => 'Produk Harian 3',
                'price'         => 600000,
                'daily_profit'  => 420000,
                'duration_days' => 3,
                'total_profit'  => 420000 * 3,
                'min_vip_level' => 3,
                'is_active'     => 1,
                'created_at'    => now(),
                'updated_at'    => now(),
            ],

            // ======================
            // PREMIUM
            // ======================
            [
                'category_id'   => $premium->id,
                'name'          => 'Produk Premium 1',
                'price'         => 100000,
                'daily_profit'  => 37000,
                'duration_days' => 90,
                'total_profit'  => 37000 * 90,
                'min_vip_level' => 1,
                'is_active'     => 1,
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'category_id'   => $premium->id,
                'name'          => 'Produk Premium 2',
                'price'         => 250000,
                'daily_profit'  => 95000,
                'duration_days' => 90,
                'total_profit'  => 95000 * 90,
                'min_vip_level' => 1,
                'is_active'     => 1,
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
        ]);
    }
}
