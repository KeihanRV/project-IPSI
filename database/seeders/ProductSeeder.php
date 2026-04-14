<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        Product::insert([
            [
                'title' => 'Sepatu Sneakers Kasual',
                'price' => 150000,
                'rating' => 4.8,
                'sold' => 1200,
                'location' => 'Jakarta Selatan',
                'image' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Tas Ransel Pria Anti Air',
                'price' => 250000,
                'rating' => 4.9,
                'sold' => 3500,
                'location' => 'Jakarta Selatan',
                'image' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Topi Baseball Polos',
                'price' => 99000,
                'rating' => 4.7,
                'sold' => 800,
                'location' => 'Jakarta Selatan',
                'image' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Kaos Los Polos Hermanos',
                'price' => 99000,
                'rating' => 4.7,
                'sold' => 800,
                'location' => 'Jakarta Selatan',
                'image' => 'product/gus.png',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
