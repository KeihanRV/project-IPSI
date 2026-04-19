<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Variant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class VariantController extends Controller
{
    /**
     * Get all variants for a product
     */
    public function index($productId)
    {
        $variants = Variant::where('product_id', $productId)->get();

        return response()->json([
            'success' => true,
            'data' => $variants->map(function ($variant) {
                return $this->formatVariant($variant);
            })
        ]);
    }

    /**
     * Get single variant
     */
    public function show($productId, $variantId)
    {
        $variant = Variant::where('product_id', $productId)
            ->findOrFail($variantId);

        return response()->json([
            'success' => true,
            'data' => $this->formatVariant($variant)
        ]);
    }

    /**
     * Store new variant for a product
     */
    public function store(Request $request, $productId)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|integer|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        try {
            $variantImageName = null;
            if ($request->hasFile('image')) {
                $path = $request->file('image')->store('variant', 'public');
                $variantImageName = basename($path);
            }

            $variant = Variant::create([
                'product_id' => $productId,
                'name' => $validated['name'],
                'price' => $validated['price'],
                'stock' => $validated['stock'],
                'image' => $variantImageName,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Varian berhasil ditambahkan',
                'data' => $this->formatVariant($variant)
            ], 201);

        } catch (\Exception $e) {
            Log::error('Error saving variant: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan varian',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update variant
     */
    public function update(Request $request, $productId, $variantId)
    {
        $variant = Variant::where('product_id', $productId)
            ->findOrFail($variantId);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|integer|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        try {
            if ($request->hasFile('image')) {
                // Hapus gambar lama
                if ($variant->image && Storage::disk('public')->exists('variant/' . $variant->image)) {
                    Storage::disk('public')->delete('variant/' . $variant->image);
                }
                $path = $request->file('image')->store('variant', 'public');
                $validated['image'] = basename($path);
            }

            $variant->update([
                'name' => $validated['name'],
                'price' => $validated['price'],
                'stock' => $validated['stock'],
                'image' => $validated['image'] ?? $variant->image,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Varian berhasil diperbarui',
                'data' => $this->formatVariant($variant)
            ]);

        } catch (\Exception $e) {
            Log::error('Error updating variant: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui varian',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete variant
     */
    public function destroy($productId, $variantId)
    {
        $variant = Variant::where('product_id', $productId)
            ->findOrFail($variantId);

        try {
            // Hapus gambar
            if ($variant->image && Storage::disk('public')->exists('variant/' . $variant->image)) {
                Storage::disk('public')->delete('variant/' . $variant->image);
            }

            $variant->delete();

            return response()->json([
                'success' => true,
                'message' => 'Varian berhasil dihapus'
            ]);

        } catch (\Exception $e) {
            Log::error('Error deleting variant: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus varian',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Format variant for response
     */
    private function formatVariant($variant)
    {
        return [
            'id' => $variant->id,
            'product_id' => $variant->product_id,
            'name' => $variant->name,
            'price' => $variant->price,
            'stock' => $variant->stock,
            'image' => $variant->image ? asset('storage/variant/' . $variant->image) : null,
            'created_at' => $variant->created_at,
            'updated_at' => $variant->updated_at,
        ];
    }
}
