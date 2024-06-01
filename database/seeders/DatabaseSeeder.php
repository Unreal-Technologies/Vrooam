<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Coupon;
use Illuminate\Database\Seeder;
use App\Logic\CouponTypes;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Product::factory()->create([
            'description' => 'Product A',
            'text' => 'content of product A',
            'price' => 25.99,
            'code' => '0031A'
        ]);
        Product::factory()->create([
            'description' => 'Product B',
            'text' => 'content of product B',
            'price' => 39.99,
            'code' => '0021F'
        ]);
        Product::factory()->create([
            'description' => 'Product C',
            'text' => 'content of product C',
            'price' => 99.25,
            'code' => '0131G'
        ]);
        Coupon::factory()->create([
            'code' => 'VROOAM1',
            'discount' => 25,
            'type' => CouponTypes::Flat
        ]);
        Coupon::factory()->create([
            'code' => 'VROOAM2',
            'discount' => 25,
            'type' => CouponTypes::Percentage
        ]);
    }
}
