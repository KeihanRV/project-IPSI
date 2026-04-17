@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
        <div class="flex flex-col md:flex-row gap-8">
            <div class="w-full md:w-1/2 aspect-square bg-gray-100 rounded-xl flex items-center justify-center">
                @if ($product->image)
                    <img src="{{ asset($product->image) }}" alt="{{ $product->title }}" class="w-full h-full object-cover">
                @else
                    <i class="fas fa-image text-9xl text-gray-300"></i>
                @endif
            </div>

            <div class="w-full md:w-1/2">
                <nav class="mb-4">
                    <a href="{{ route('home') }}" class="text-brand text-sm hover:underline">
                        <i class="fas fa-arrow-left mr-1"></i> Kembali ke Beranda
                    </a>
                </nav>

                <h1 class="text-3xl font-bold text-gray-800 mb-2">{{ $product->title }}</h1>
                <p class="text-2xl font-bold text-gray-900 mb-4">Rp{{ number_format($product->price, 0, ',', '.') }}</p>

                <div class="border-t border-b py-4 mb-6">
                    <p class="text-gray-600 leading-relaxed">
                        Ini adalah deskripsi produk dummy untuk ID {{ $product->id }}. Di sini Anda bisa menambahkan detail
                        spesifikasi, keunggulan produk, dan informasi lainnya.
                    </p>
                </div>

                <div class="space-y-3">
                    <div class="flex items-center gap-3">
                        <label class="text-sm font-medium text-gray-700 w-16">Jumlah:</label>
                        <input type="number" name="qty" value="1" min="1" max="99"
                            class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand focus:border-brand">
                    </div>
                    <x-primary-button type="submit" class="w-full py-3 text-lg">
                        <i class="fas fa-cart-plus mr-2"></i> Tambah ke Keranjang
                    </x-primary-button>
                </div>
                </form>
            </div>
        </div>
    </div>
@endsection