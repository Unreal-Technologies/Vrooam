<?php

namespace Database\Seeders;

use App\Models\Product;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

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
            'price' => 25.99
        ]);
        Product::factory()->create([
            'description' => 'Product B',
            'text' => 'content of product B',
            'price' => 39.99
        ]);
        Product::factory()->create([
            'description' => 'Product C',
            'text' => 'content of product C',
            'price' => 99.25
        ]);
    }
}
