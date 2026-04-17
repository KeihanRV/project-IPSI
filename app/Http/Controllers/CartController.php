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

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'qty' => 'integer|min:1'
        ]);

        $success = $this->cartService->add($request->product_id, $request->qty ?? 1);

        return back()->with('success', $success ? 'Ditambahkan ke keranjang!' : 'Produk tidak ditemukan.');
    }

    public function update(Request $request)
    {
        $request->validate([
            'items.*.qty' => 'integer|min:1',
            'items.*.id' => 'exists:products,id'
        ]);

        foreach ($request->items as $item) {
            $this->cartService->update($item['id'], $item['qty']);
        }

        return back()->with('success', 'Keranjang diperbarui!');
    }


    public function remove($id)
    {
        $this->cartService->remove($id);
        return back()->with('success', 'Item dihapus!');
    }


    public function clear()
    {
        $this->cartService->clear();
        return back()->with('success', 'Keranjang dikosongkan!');
    }
}

