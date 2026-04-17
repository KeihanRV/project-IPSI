<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Variant;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // Produk 1: Sepatu Sneakers Kasual
        $product1 = Product::create([
            'title' => 'Sepatu Sneakers Kasual Premium',
            'description' => 'Sepatu sneakers kasual dengan desain minimalis yang cocok untuk berbagai aktivitas sehari-hari. Material berkualitas tinggi dengan kenyamanan maksimal.',
            'specification' => "• Material: Canvas dan Rubber\n• Sol: Rubber Anti-Slip\n• Ketersediaan: All Sizes\n• Garansi: 1 Tahun\n• Berat per pasang: 400g",
            'location' => 'Jakarta Selatan',
            'image' => null,
            'rating' => 4.8,
            'sold' => 1200,
        ]);

        $product1->variants()->createMany([
            ['name' => 'Size 38', 'price' => 189000, 'stock' => 50, 'image' => null],
            ['name' => 'Size 39', 'price' => 189000, 'stock' => 75, 'image' => null],
            ['name' => 'Size 40', 'price' => 189000, 'stock' => 100, 'image' => null],
            ['name' => 'Size 41', 'price' => 189000, 'stock' => 60, 'image' => null],
            ['name' => 'Size 42', 'price' => 189000, 'stock' => 45, 'image' => null],
        ]);

        // Produk 2: Tas Ransel Pria Anti Air
        $product2 = Product::create([
            'title' => 'Tas Ransel Pria Anti Air Oxford',
            'description' => 'Tas ransel pria dengan teknologi anti air yang sempurna untuk perjalanan bisnis, traveling, atau aktivitas outdoor. Desain ergonomis dengan kompartemen yang lengkap.',
            'specification' => "• Material: Oxford Waterproof\n• Kapasitas: 30-40 Liter\n• Kompartemen: 5+ Pockets\n• Garansi: 2 Tahun\n• Berat: 800g",
            'location' => 'Jakarta Selatan',
            'image' => null,
            'rating' => 4.9,
            'sold' => 3500,
        ]);

        $product2->variants()->createMany([
            ['name' => 'Hitam', 'price' => 299000, 'stock' => 80, 'image' => null],
            ['name' => 'Biru Navy', 'price' => 299000, 'stock' => 60, 'image' => null],
            ['name' => 'Abu-abu', 'price' => 299000, 'stock' => 70, 'image' => null],
            ['name' => 'Coklat', 'price' => 329000, 'stock' => 50, 'image' => null],
        ]);

        // Produk 3: Topi Baseball Polos
        $product3 = Product::create([
            'title' => 'Topi Baseball Polos Cotton Twill',
            'description' => 'Topi baseball dengan desain polos yang versatile. Terbuat dari material cotton twill berkualitas tinggi, nyaman dipakai dalam kondisi apapun.',
            'specification' => "• Material: 100% Cotton Twill\n• Tali Pengatur: Adjustable Velcro\n• Ukiran Logo: Custom Available\n• Garansi: 6 Bulan\n• UV Protection: 50+",
            'location' => 'Jakarta Selatan',
            'image' => null,
            'rating' => 4.7,
            'sold' => 800,
        ]);

        $product3->variants()->createMany([
            ['name' => 'Putih', 'price' => 79000, 'stock' => 120, 'image' => null],
            ['name' => 'Hitam', 'price' => 79000, 'stock' => 150, 'image' => null],
            ['name' => 'Merah', 'price' => 79000, 'stock' => 90, 'image' => null],
            ['name' => 'Biru', 'price' => 79000, 'stock' => 100, 'image' => null],
        ]);

        // Produk 4: Kaos Los Polos Hermanos
        $product4 = Product::create([
            'title' => 'Kaos Los Polos Hermanos',
            'description' => 'Kaos dengan desain Los Polos Hermanos yang iconic. Bahan premium cotton yang empuk dan breathable, sempurna untuk aktivitas casual.',
            'specification' => "• Material: 100% Premium Cotton\n• Gramasi: 230gsm\n• Jahitan: Double Stitch\n• Printing: Screen Print\n• Garansi: 1 Tahun",
            'location' => 'Jakarta Selatan',
            'image' => 'gus.png',
            'rating' => 4.7,
            'sold' => 800,
        ]);

        $product4->variants()->createMany([
            ['name' => 'Size S', 'price' => 129000, 'stock' => 80, 'image' => null],
            ['name' => 'Size M', 'price' => 129000, 'stock' => 150, 'image' => null],
            ['name' => 'Size L', 'price' => 129000, 'stock' => 120, 'image' => null],
            ['name' => 'Size XL', 'price' => 139000, 'stock' => 90, 'image' => null],
            ['name' => 'Size XXL', 'price' => 149000, 'stock' => 60, 'image' => null],
        ]);

        // Produk 5: Jam Tangan Digital Sport
        $product5 = Product::create([
            'title' => 'Jam Tangan Digital Sport Waterproof',
            'description' => 'Jam tangan digital dengan fitur sport tracking dan waterproof hingga 50 meter. Ideal untuk aktivitas outdoor dan olahraga.',
            'specification' => "• Air Resistance: 50 Meter\n• Display: LCD Digital\n• Fitur: Stopwatch, Timer, Alarm\n• Baterai: 2 Tahun\n• Material: Rubber & Stainless Steel",
            'location' => 'Jakarta Selatan',
            'image' => null,
            'rating' => 4.6,
            'sold' => 520,
        ]);

        $product5->variants()->createMany([
            ['name' => 'Hitam', 'price' => 249000, 'stock' => 40, 'image' => null],
            ['name' => 'Biru', 'price' => 249000, 'stock' => 35, 'image' => null],
            ['name' => 'Merah', 'price' => 249000, 'stock' => 30, 'image' => null],
        ]);
    }
}
