<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CartService
{
    /**
     * Get cart items for current user/session
     */
    public function getCart()
    {
        return Cart::forCurrentUser()
            ->with(['product', 'variant'])
            ->get()
            ->keyBy('id');
    }

    /**
     * Add product to cart
     */
    public function add($productId, $qty = 1, $variantId = null)
    {
        $product = Product::find($productId);

        if (!$product) {
            return false;
        }

        // Check if variant exists and belongs to the product
        if ($variantId) {
            $variant = $product->variants()->find($variantId);
            if (!$variant) {
                return false;
            }
        }

        // Find existing cart item
        $cartItem = Cart::forCurrentUser()
            ->where('product_id', $productId)
            ->where('variant_id', $variantId)
            ->first();

        if ($cartItem) {
            // Update quantity
            $cartItem->increment('quantity', $qty);
        } else {
            // Create new cart item
            Cart::create([
                'user_id' => Auth::id(),
                'session_id' => Auth::check() ? null : Session::getId(),
                'product_id' => $productId,
                'variant_id' => $variantId,
                'quantity' => $qty,
            ]);
        }

        return true;
    }

    /**
     * Update cart item quantity
     */
    public function update($productId, $qty, $variantId = null)
    {
        $cartItem = Cart::forCurrentUser()
            ->where('product_id', $productId)
            ->where('variant_id', $variantId)
            ->first();

        if ($cartItem) {
            if ($qty <= 0) {
                $cartItem->delete();
            } else {
                $cartItem->update(['quantity' => $qty]);
            }
        }
    }

    public function updateById($cartId, $qty)
    {
        $cartItem = Cart::forCurrentUser()->find($cartId);

        if ($cartItem) {
            if ($qty <= 0) {
                $cartItem->delete();
            } else {
                $cartItem->update(['quantity' => $qty]);
            }
        }
    }

    /**
     * Remove item from cart
     */
    public function remove($productId, $variantId = null)
    {
        Cart::forCurrentUser()
            ->where('product_id', $productId)
            ->where('variant_id', $variantId)
            ->delete();
    }

    public function removeById($cartId)
    {
        Cart::forCurrentUser()->find($cartId)?->delete();
    }

    /**
     * Clear all cart items
     */
    public function clear()
    {
        Cart::forCurrentUser()->delete();
    }

    /**
     * Get total items count
     */
    public function count()
    {
        return Cart::forCurrentUser()->sum('quantity');
    }

    /**
     * Get total price
     */
    public function total()
    {
        return Cart::forCurrentUser()
            ->with(['product', 'variant'])
            ->get()
            ->sum(function ($item) {
                return $item->subtotal;
            });
    }

    /**
     * Get cart items formatted for views
     */
    public function getItems()
    {
        return Cart::forCurrentUser()
            ->with(['product', 'variant'])
            ->get()
            ->mapWithKeys(function ($item) {
                return [
                    $item->id => [
                        'name' => $item->name,
                        'variant' => $item->variant?->name,
                        'price' => $item->price,
                        'qty' => $item->quantity,
                        'image' => $item->image,
                        'subtotal' => $item->subtotal,
                    ]
                ];
            })
            ->toArray();
    }

    /**
     * Migrate session cart to database (for login)
     */
    public function migrateSessionCartToUser($userId)
    {
        $sessionId = Session::getId();

        // Get all session cart items
        $sessionCartItems = Cart::where('session_id', $sessionId)->get();

        foreach ($sessionCartItems as $item) {
            // Check if user already has this item
            $existingItem = Cart::where('user_id', $userId)
                ->where('product_id', $item->product_id)
                ->where('variant_id', $item->variant_id)
                ->first();

            if ($existingItem) {
                // Merge quantities
                $existingItem->increment('quantity', $item->quantity);
                $item->delete();
            } else {
                // Transfer to user
                $item->update([
                    'user_id' => $userId,
                    'session_id' => null,
                ]);
            }
        }
    }
}

