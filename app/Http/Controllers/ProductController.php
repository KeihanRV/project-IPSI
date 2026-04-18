<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $products = Product::when($search, function ($query, $search) {
            return $query->where('title', 'like', '%' . $search . '%')
                ->orWhere('location', 'like', '%' . $search . '%');
        })->get();

        return view('pages.home', compact('products'));
    }

    public function show($id)
    {
        $product = Product::with('variants')->findOrFail($id);

        // Format for view
        $product->lowest_price = $product->variants->min('price') ?? 0;
        $product->specs = explode("\n", $product->specification ?? '');

        return view('pages.product-detail', compact('product'));
    }

    public function create()
    {
        return view('admin.create-product');
    }

    public function store(Request $request)
    {
        // Validasi data utama produk
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'specification' => 'nullable|string',
            'location' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048', // Max 2MB
            'variants' => 'required|array|min:1', // Minimal 1 varian
            'variants.*.name' => 'required|string|max:255',
            'variants.*.price' => 'required|integer|min:0',
            'variants.*.stock' => 'required|integer|min:0',
            'variants.*.image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048', // Max 2MB per gambar varian
        ]);

        // Mulai transaksi database
        DB::beginTransaction();

        try {
            // Handle upload gambar utama produk
            $productImageName = null;
            if ($request->hasFile('image')) {
                $path = $request->file('image')->store('product', 'public');
                $productImageName = basename($path);
            }

            // Buat produk
            $product = Product::create([
                'title' => $validated['title'],
                'description' => $validated['description'],
                'specification' => $validated['specification'],
                'location' => $validated['location'],
                'image' => $productImageName,
                'rating' => 0,
                'sold' => 0,
            ]);

            // Simpan varian
            foreach ($validated['variants'] as $variantData) {
                $variantImageName = null;
                if (isset($variantData['image']) && $variantData['image']) {
                    // Cari file varian berdasarkan index
                    $index = array_search($variantData, $validated['variants']);
                    if ($request->hasFile("variants.{$index}.image")) {
                        $path = $request->file("variants.{$index}.image")->store('variant', 'public');
                        $variantImageName = basename($path);
                    }
                }

                $product->variants()->create([
                    'name' => $variantData['name'],
                    'price' => $variantData['price'],
                    'stock' => $variantData['stock'],
                    'image' => $variantImageName,
                ]);
            }

            // Commit transaksi jika semua berhasil
            DB::commit();

            return redirect()->route('admin.dashboard')->with('success', 'Produk berhasil ditambahkan!');

        } catch (\Exception $e) {
            // Rollback jika terjadi error
            DB::rollBack();

            // Hapus gambar yang sudah diupload jika ada error
            if ($productImageName && Storage::disk('public')->exists('product/' . $productImageName)) {
                Storage::disk('public')->delete('product/' . $productImageName);
            }

            // Log error untuk debugging
            Log::error('Error saving product: ' . $e->getMessage());

            return redirect()->route('admin.products.create')->with('error', 'Terjadi kesalahan saat menyimpan produk. Silakan coba lagi.');
        }
    }

    public function edit($id)
    {
        $product = Product::with('variants')->findOrFail($id);

        // Format variants dengan URL gambar untuk JavaScript
        $product->variants_data = $product->variants->map(function ($variant) {
            return [
                'id' => $variant->id,
                'name' => $variant->name,
                'price' => $variant->price,
                'stock' => $variant->stock,
                'image' => $variant->image ? asset('storage/variant/' . $variant->image) : null,
            ];
        });

        return view('admin.edit-product', compact('product'));
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        // Validasi data utama produk
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'specification' => 'nullable|string',
            'location' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048', // Max 2MB
            'variants' => 'required|array|min:1', // Minimal 1 varian
            'variants.*.name' => 'required|string|max:255',
            'variants.*.price' => 'required|integer|min:0',
            'variants.*.stock' => 'required|integer|min:0',
            'variants.*.image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048', // Max 2MB per gambar varian
        ]);

        // Mulai transaksi database
        DB::beginTransaction();

        try {
            // Handle upload gambar utama produk
            $productImageName = $product->image; // Default ke yang lama
            if ($request->hasFile('image')) {
                // Hapus gambar lama jika ada
                if ($product->image && Storage::disk('public')->exists('product/' . $product->image)) {
                    Storage::disk('public')->delete('product/' . $product->image);
                }
                $path = $request->file('image')->store('product', 'public');
                $productImageName = basename($path);
            }

            // Update produk
            $product->update([
                'title' => $validated['title'],
                'description' => $validated['description'],
                'specification' => $validated['specification'],
                'location' => $validated['location'],
                'image' => $productImageName,
            ]);

            // Ambil ID varian yang ada di request
            $existingVariantIds = collect($validated['variants'])->pluck('id')->filter()->toArray();

            // Hapus varian yang tidak ada di request
            $product->variants()->whereNotIn('id', $existingVariantIds)->each(function ($variant) {
                if ($variant->image && Storage::disk('public')->exists('variant/' . $variant->image)) {
                    Storage::disk('public')->delete('variant/' . $variant->image);
                }
                $variant->delete();
            });

            // Update atau buat varian baru
            foreach ($validated['variants'] as $index => $variantData) {
                $variantImageName = null;
                if (isset($variantData['image']) && $variantData['image']) {
                    if ($request->hasFile("variants.{$index}.image")) {
                        $path = $request->file("variants.{$index}.image")->store('variant', 'public');
                        $variantImageName = basename($path);
                    }
                }

                if (isset($variantData['id']) && $variantData['id']) {
                    // Update varian yang ada
                    $variant = $product->variants()->find($variantData['id']);
                    if ($variant) {
                        // Hapus gambar lama jika ada gambar baru
                        if ($variantImageName && $variant->image && Storage::disk('public')->exists('variant/' . $variant->image)) {
                            Storage::disk('public')->delete('variant/' . $variant->image);
                        }
                        $variant->update([
                            'name' => $variantData['name'],
                            'price' => $variantData['price'],
                            'stock' => $variantData['stock'],
                            'image' => $variantImageName ?: $variant->image, // Jika tidak ada gambar baru, tetap yang lama
                        ]);
                    }
                } else {
                    // Buat varian baru
                    $product->variants()->create([
                        'name' => $variantData['name'],
                        'price' => $variantData['price'],
                        'stock' => $variantData['stock'],
                        'image' => $variantImageName,
                    ]);
                }
            }

            // Commit transaksi jika semua berhasil
            DB::commit();

            return redirect()->route('admin.dashboard')->with('success', 'Produk berhasil diperbarui!');

        } catch (\Exception $e) {
            // Rollback jika terjadi error
            DB::rollBack();

            // Log error untuk debugging
            Log::error('Error updating product: ' . $e->getMessage());

            return redirect()->route('admin.products.edit', $id)->with('error', 'Terjadi kesalahan saat memperbarui produk. Silakan coba lagi.');
        }
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        // Mulai transaksi database
        DB::beginTransaction();

        try {
            // Hapus gambar produk jika ada
            if ($product->image && Storage::disk('public')->exists('product/' . $product->image)) {
                Storage::disk('public')->delete('product/' . $product->image);
            }

            // Hapus gambar varian dan varian
            $product->variants->each(function ($variant) {
                if ($variant->image && Storage::disk('public')->exists('variant/' . $variant->image)) {
                    Storage::disk('public')->delete('variant/' . $variant->image);
                }
                $variant->delete();
            });

            // Hapus produk
            $product->delete();

            // Commit transaksi
            DB::commit();

            return redirect()->route('admin.dashboard')->with('success', 'Produk berhasil dihapus!');

        } catch (\Exception $e) {
            // Rollback jika terjadi error
            DB::rollBack();

            // Log error untuk debugging
            Log::error('Error deleting product: ' . $e->getMessage());

            return redirect()->route('admin.dashboard')->with('error', 'Terjadi kesalahan saat menghapus produk. Silakan coba lagi.');
        }
    }


}
