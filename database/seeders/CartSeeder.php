<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\User;
use App\Services\CartService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Auth;

class CartSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if user exists
        $user = User::find(11);
        if (!$user) {
            $this->command->error('❌ User with ID 1 not found. Please run UserSeeder first.');
            return;
        }

        // Clear existing cart for test user
        \App\Models\Cart::where('user_id', 1)->delete();

        // Get products with variants
        $products = Product::with('variants')->whereHas('variants')->take(5)->get();

        if ($products->isEmpty()) {
            $this->command->error('❌ No products with variants found. Please run ProductSeeder first.');
            return;
        }

        $cartService = new CartService();

        // Simulate user login
        Auth::login($user);

        $addedItems = 0;
        $skippedItems = 0;

        $this->command->info('🛒 Starting cart seeding...');
        $this->command->info('');

        foreach ($products as $index => $product) {
            // Get first variant for this product
            $variant = $product->variants->first();

            if (!$variant) {
                $this->command->warn("⚠️  Product '{$product->title}' has no variants, skipping...");
                $skippedItems++;
                continue;
            }

            // Calculate quantity to add (ensure it doesn't exceed stock)
            $maxQty = min($variant->stock, 5); // Max 5 items per product for testing
            $quantity = min($index + 1, $maxQty); // 1, 2, 3, 4, 5...

            if ($quantity <= 0) {
                $this->command->warn("⚠️  Variant '{$variant->name}' has no stock, skipping...");
                $skippedItems++;
                continue;
            }

            // Add to cart using CartService
            $success = $cartService->add($product->id, $quantity, $variant->id);

            if ($success) {
                $this->command->info("✅ Added {$quantity}x {$product->title} ({$variant->name}) to cart");
                $addedItems++;
            } else {
                $this->command->error("❌ Failed to add {$product->title} to cart");
                $skippedItems++;
            }
        }

        // Logout after seeding
        Auth::logout();

        $this->command->info('');
        $this->command->info('📋 Cart Summary:');

        // Get cart items for display
        $cartItems = \App\Models\Cart::where('user_id', 1)->with(['product', 'variant'])->get();
        $totalItems = 0;
        $totalPrice = 0;

        foreach ($cartItems as $item) {
            $subtotal = $item->subtotal;
            $totalItems += $item->quantity;
            $totalPrice += $subtotal;

            $variantName = $item->variant ? " ({$item->variant->name})" : '';
            $this->command->info("  • {$item->product->title}{$variantName} x{$item->quantity} - Rp" . number_format($subtotal, 0, ',', '.'));
        }

        $this->command->info('');
        $this->command->info("📊 Total: {$totalItems} items - Rp" . number_format($totalPrice, 0, ',', '.'));
        $this->command->info('');
        $this->command->info("✅ Cart seeded successfully! Added: {$addedItems}, Skipped: {$skippedItems}");
        $this->command->info('');
        $this->command->info('💡 Tips for testing:');
        $this->command->info('   • Login as user ID 1 to see the cart');
        $this->command->info('   • Try updating quantities in cart page');
        $this->command->info('   • Test stock validation by adding more than available stock');
    }
}