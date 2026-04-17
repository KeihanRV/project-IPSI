<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Services\CartService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class CartSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing cart for test user
        \App\Models\Cart::where('user_id', 1)->delete();

        // Get some products to add to cart
        $products = Product::with('variants')->take(3)->get();

        if ($products->isEmpty()) {
            $this->command->info('No products found. Please run ProductSeeder first.');
            return;
        }

        $cartService = new CartService();

        // Simulate user login for cart operations
        Auth::loginUsingId(1);

        // Add products to cart with different quantities
        foreach ($products as $index => $product) {
            $quantity = $index + 1; // 1, 2, 3 items
            $cartService->add($product->id, $quantity);

            $this->command->info("✓ Added {$quantity}x {$product->title} to cart");
        }

        // Show cart summary
        $cartItems = \App\Models\Cart::forCurrentUser()->with(['product', 'variant'])->get();
        $totalItems = 0;
        $totalPrice = 0;

        $this->command->info('');
        $this->command->info('📋 Cart Summary:');
        foreach ($cartItems as $item) {
            $subtotal = $item->subtotal;
            $totalItems += $item->quantity;
            $totalPrice += $subtotal;

            $this->command->info("  • {$item->name} (x{$item->quantity}) - Rp" . number_format($subtotal, 0, ',', '.'));
        }

        $this->command->info('');
        $this->command->info("📊 Total: {$totalItems} items - Rp" . number_format($totalPrice, 0, ',', '.'));
        $this->command->info('');
        $this->command->info('✅ Cart seeded successfully to database!');
    }
}