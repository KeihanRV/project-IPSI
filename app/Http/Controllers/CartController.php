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
        $count = $this->cartService->count();

        return view('pages.cart', compact('items', 'total', 'count'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'qty' => 'sometimes|integer|min:1|max:99'
        ]);

        $success = $this->cartService->add($request->product_id, $request->qty ?? 1);

        if ($success) {
            return redirect()->route('cart.index')->with('success', 'Produk ditambahkan ke keranjang!');
        }

        return redirect()->back()->with('error', 'Produk tidak ditemukan.');
    }

    public function update(Request $request)
    {
        $request->validate([
            'updates.*.product_id' => 'required|exists:products,id',
            'updates.*.qty' => 'required|integer|min:0|max:99'
        ]);

        foreach ($request->updates as $update) {
            $this->cartService->update($update['product_id'], $update['qty']);
        }

        return redirect()->route('cart.index')->with('success', 'Keranjang diperbarui!');
    }

    public function remove($id)
    {
        $this->cartService->remove($id);
        return redirect()->route('cart.index')->with('success', 'Item dihapus dari keranjang.');
    }

    public function clear()
    {
        $this->cartService->clear();
        return redirect()->route('cart.index')->with('success', 'Keranjang dikosongkan.');
    }
}

