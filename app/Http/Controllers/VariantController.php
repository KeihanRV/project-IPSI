<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class VariantController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'location' => 'required|string',
            'image' => 'nullable|image|max:2048',
            // price utama sudah tidak ada
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('product', 'public');
            $validated['image'] = basename($path); 
        }

        $validated['rating'] = 0;
        $validated['sold'] = 0;

        // Simpan produk utama
        $product = Product::create($validated);

        // ✅ Simpan Varian & Foto Varian
        if ($request->has('variants')) {
            foreach ($request->variants as $index => $variant) {
                if (!empty($variant['name']) && !empty($variant['price'])) {
                    
                    $variantImageName = null;
                    
                    // Cek apakah ada file foto varian yang diunggah
                    if ($request->hasFile("variants.{$index}.image")) {
                        $variantPath = $request->file("variants.{$index}.image")->store('variant', 'public');
                        $variantImageName = basename($variantPath);
                    }

                    $product->variants()->create([
                        'name' => $variant['name'],
                        'price' => $variant['price'],
                        'image' => $variantImageName, // Simpan nama fotonya
                    ]);
                }
            }
        }

        return redirect()->route('admin.dashboard')->with('success', 'Produk berhasil ditambahkan!');
    }
    
}
