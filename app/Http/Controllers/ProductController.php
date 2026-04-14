<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        // Tangkap keyword pencarian
        $search = $request->input('search');

        // Query database
        $products = Product::when($search, function ($query, $search) {
            return $query->where('title', 'like', '%' . $search . '%')
                         ->orWhere('location', 'like', '%' . $search . '%'); // (Opsional) Bisa mencari berdasarkan lokasi juga
        })->get();

        return view('pages.home', compact('products'));
    }

    public function show($id)
    {
        $product = Product::findOrFail($id); 
        return view('testing.detail', compact('product'));
    }
}
