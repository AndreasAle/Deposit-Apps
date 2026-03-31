<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductCategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            'Reguler',
            'Harian',
            'Premium',
        ];

        foreach ($categories as $cat) {
            DB::table('product_categories')->updateOrInsert(
                ['slug' => Str::slug($cat)],
                [
                    'name'       => $cat,
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );
        }
    }
}
