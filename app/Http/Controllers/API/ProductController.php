<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Variant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    /**
     * Get all products with variants
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('per_page', 15);

        $products = Product::with('variants')
            ->when($search, function ($query, $search) {
                return $query->where('title', 'like', '%' . $search . '%')
                    ->orWhere('location', 'like', '%' . $search . '%');
            })
            ->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $products->items(),
            'pagination' => [
                'current_page' => $products->currentPage(),
                'total' => $products->total(),
                'per_page' => $products->perPage(),
                'last_page' => $products->lastPage(),
            ]
        ]);
    }

    /**
     * Get single product with all variants
     */
    public function show($id)
    {
        $product = Product::with('variants')->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $this->formatProductWithVariants($product)
        ]);
    }

    /**
     * Store new product with variants
     */
    public function store(Request $request)
    {
        // Validasi data utama produk
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'specification' => 'nullable|string',
            'location' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'variants' => 'required|array|min:1',
            'variants.*.name' => 'required|string|max:255',
            'variants.*.price' => 'required|integer|min:0',
            'variants.*.stock' => 'required|integer|min:0',
            'variants.*.image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
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
            foreach ($validated['variants'] as $index => $variantData) {
                $variantImageName = null;
                if ($request->hasFile("variants.{$index}.image")) {
                    $path = $request->file("variants.{$index}.image")->store('variant', 'public');
                    $variantImageName = basename($path);
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

            // Load variants untuk response
            $product->load('variants');

            return response()->json([
                'success' => true,
                'message' => 'Produk berhasil ditambahkan',
                'data' => $this->formatProductWithVariants($product)
            ], 201);

        } catch (\Exception $e) {
            // Rollback jika terjadi error
            DB::rollBack();

            // Hapus gambar yang sudah diupload jika ada error
            if ($productImageName && Storage::disk('public')->exists('product/' . $productImageName)) {
                Storage::disk('public')->delete('product/' . $productImageName);
            }

            // Log error untuk debugging
            Log::error('Error saving product: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan produk',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get product for editing (with current image URLs)
     */
    public function edit($id)
    {
        $product = Product::with('variants')->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $product->id,
                'title' => $product->title,
                'description' => $product->description,
                'specification' => $product->specification,
                'location' => $product->location,
                'image' => $product->image ? asset('storage/product/' . $product->image) : null,
                'variants' => $product->variants->map(function ($variant) {
                    return [
                        'id' => $variant->id,
                        'name' => $variant->name,
                        'price' => $variant->price,
                        'stock' => $variant->stock,
                        'image' => $variant->image ? asset('storage/variant/' . $variant->image) : null,
                    ];
                })
            ]
        ]);
    }

    /**
     * Update product with variants
     */
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        // Validasi data utama produk
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'specification' => 'nullable|string',
            'location' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'variants' => 'required|array|min:1',
            'variants.*.id' => 'nullable|integer|exists:variants,id',
            'variants.*.name' => 'required|string|max:255',
            'variants.*.price' => 'required|integer|min:0',
            'variants.*.stock' => 'required|integer|min:0',
            'variants.*.image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        DB::beginTransaction();

        try {
            // Handle update gambar utama produk
            if ($request->hasFile('image')) {
                // Hapus gambar lama jika ada
                if ($product->image && Storage::disk('public')->exists('product/' . $product->image)) {
                    Storage::disk('public')->delete('product/' . $product->image);
                }
                $path = $request->file('image')->store('product', 'public');
                $validated['image'] = basename($path);
            }

            // Update data produk
            $product->update([
                'title' => $validated['title'],
                'description' => $validated['description'],
                'specification' => $validated['specification'],
                'location' => $validated['location'],
                'image' => $validated['image'] ?? $product->image,
            ]);

            // Get existing variant IDs
            $existingVariantIds = $product->variants()->pluck('id')->toArray();
            $submittedVariantIds = [];

            // Update atau buat varian
            foreach ($validated['variants'] as $index => $variantData) {
                $variantId = $variantData['id'] ?? null;
                $variantImageName = null;

                // Jika ada file image baru untuk varian
                if ($request->hasFile("variants.{$index}.image")) {
                    $path = $request->file("variants.{$index}.image")->store('variant', 'public');
                    $variantImageName = basename($path);
                }

                if ($variantId) {
                    // Update variant yang sudah ada
                    $variant = Variant::find($variantId);
                    
                    if ($variantImageName) {
                        // Hapus gambar lama jika ada
                        if ($variant->image && Storage::disk('public')->exists('variant/' . $variant->image)) {
                            Storage::disk('public')->delete('variant/' . $variant->image);
                        }
                        $variant->image = $variantImageName;
                    }

                    $variant->update([
                        'name' => $variantData['name'],
                        'price' => $variantData['price'],
                        'stock' => $variantData['stock'],
                        'image' => $variantImageName ? $variantImageName : $variant->image,
                    ]);

                    $submittedVariantIds[] = $variantId;
                } else {
                    // Buat variant baru
                    $newVariant = $product->variants()->create([
                        'name' => $variantData['name'],
                        'price' => $variantData['price'],
                        'stock' => $variantData['stock'],
                        'image' => $variantImageName,
                    ]);
                    $submittedVariantIds[] = $newVariant->id;
                }
            }

            // Hapus varian yang tidak ada di request
            $variantsToDelete = array_diff($existingVariantIds, $submittedVariantIds);
            foreach ($variantsToDelete as $variantId) {
                $variant = Variant::find($variantId);
                if ($variant->image && Storage::disk('public')->exists('variant/' . $variant->image)) {
                    Storage::disk('public')->delete('variant/' . $variant->image);
                }
                $variant->delete();
            }

            DB::commit();

            // Reload product dengan variants terbaru
            $product->load('variants');

            return response()->json([
                'success' => true,
                'message' => 'Produk berhasil diperbarui',
                'data' => $this->formatProductWithVariants($product)
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating product: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui produk',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete product
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        DB::beginTransaction();

        try {
            // Hapus semua gambar varian
            foreach ($product->variants as $variant) {
                if ($variant->image && Storage::disk('public')->exists('variant/' . $variant->image)) {
                    Storage::disk('public')->delete('variant/' . $variant->image);
                }
            }

            // Hapus gambar produk
            if ($product->image && Storage::disk('public')->exists('product/' . $product->image)) {
                Storage::disk('public')->delete('product/' . $product->image);
            }

            // Hapus variants
            $product->variants()->delete();

            // Hapus produk
            $product->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Produk berhasil dihapus'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting product: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus produk',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Format product with variants for response
     */
    private function formatProductWithVariants($product)
    {
        return [
            'id' => $product->id,
            'title' => $product->title,
            'description' => $product->description,
            'specification' => $product->specification,
            'location' => $product->location,
            'image' => $product->image ? asset('storage/product/' . $product->image) : null,
            'rating' => $product->rating,
            'sold' => $product->sold,
            'lowest_price' => $product->lowest_price,
            'total_stock' => $product->total_stock,
            'variants' => $product->variants->map(function ($variant) {
                return [
                    'id' => $variant->id,
                    'name' => $variant->name,
                    'price' => $variant->price,
                    'stock' => $variant->stock,
                    'image' => $variant->image ? asset('storage/variant/' . $variant->image) : null,
                ];
            })->toArray(),
            'created_at' => $product->created_at,
            'updated_at' => $product->updated_at,
        ];
    }
}
