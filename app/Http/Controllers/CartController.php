<?php

namespace App\Http\Controllers;

use App\Services\CartService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    protected $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function index()
    {
        $items = $this->cartService->getItems();
        $total = $this->cartService->total();
        return view('pages.cart', compact('items', 'total'));
    }

    public function addToCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'variant_id' => 'nullable|exists:variants,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $productId = $request->product_id;
        $variantId = $request->variant_id ? (int) $request->variant_id : null;
        $quantity = $request->quantity;
        $userId = auth()->id();
        $sessionId = session()->getId();

        // Check if cart item exists (user OR session + product_id + variant_id)
        $cartItem = \App\Models\Cart::where(function ($query) use ($userId, $sessionId) {
            if ($userId) {
                $query->where('user_id', $userId);
            } else {
                $query->where('session_id', $sessionId);
            }
        })
            ->where('product_id', $productId)
            ->when($variantId, function ($query) use ($variantId) {
                $query->where('variant_id', $variantId);
            })
            ->first();

        if ($cartItem) {
            // Increment quantity
            $cartItem->increment('quantity', $quantity);
            $message = 'Keranjang diperbarui!';
        } else {
            // Create new cart item
            \App\Models\Cart::create([
                'user_id' => $userId,
                'session_id' => $userId ? null : $sessionId,
                'product_id' => $productId,
                'variant_id' => $variantId,
                'quantity' => $quantity,
            ]);
            $message = 'Ditambahkan ke keranjang!';
        }

        return back()->with('success', $message);
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'qty' => 'integer|min:1'
        ]);

        $success = $this->cartService->add($request->product_id, $request->qty ?? 1);

        return back()->with('success', $success ? 'Ditambahkan ke keranjang!' : 'Produk tidak ditemukan.');
    }

    public function checkout(Request $request)
    {
        $this->cartService->clear();

        return redirect('/')->with('success', 'Checkout Berhasil');
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'qty' => 'required|integer|min:1',
        ]);

        $this->cartService->updateById($id, $validated['qty']);

        return back()->with('success', 'Kuantitas keranjang diperbarui!');
    }

    public function destroy($id)
    {
        $this->cartService->removeById($id);

        return back()->with('success', 'Item dihapus dari keranjang!');
    }

    public function remove($id)
    {
        $this->cartService->removeById($id);
        return back()->with('success', 'Item dihapus!');
    }

    public function clear()
    {
        $this->cartService->clear();
        return back()->with('success', 'Keranjang dikosongkan!');
    }
}

