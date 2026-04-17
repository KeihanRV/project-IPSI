<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Facades\Session;

class CartService
{
    public function getCart()
    {
        return Session::get('cart', []);
    }

    public function add($productId, $qty = 1)
    {
        $cart = $this->getCart();
        $product = Product::find($productId);

        if (!$product) {
            return false;
        }

        if (isset($cart[$productId])) {
            $cart[$productId]['qty'] += $qty;
        } else {
            $cart[$productId] = [
                'name' => $product->title,
                'price' => $product->price,
                'qty' => $qty,
                'image' => $product->image,
            ];
        }

        Session::put('cart', $cart);
        return true;
    }

    public function update($productId, $qty)
    {
        $cart = $this->getCart();
        if ($qty <= 0) {
            unset($cart[$productId]);
        } else {
            $cart[$productId]['qty'] = $qty;
        }
        Session::put('cart', $cart);
    }

    public function remove($productId)
    {
        $cart = $this->getCart();
        unset($cart[$productId]);
        Session::put('cart', $cart);
    }

    public function clear()
    {
        Session::forget('cart');
    }

    public function count()
    {
        $cart = $this->getCart();
        return array_sum(array_column($cart, 'qty'));
    }

    public function total()
    {
        $cart = $this->getCart();
        return array_reduce($cart, fn($sum, $item) => $sum + $item['qty'] * $item['price']);
    }

    public function getItems()
    {
        return $this->getCart();
    }
}

