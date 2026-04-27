@extends('layouts.app')

@section('content')

    @if(session('success'))
        <div class="mb-6 rounded-2xl border border-[#C9A227] bg-[#F5EEDC] px-5 py-4 shadow-sm text-[#3F3B27]">
            <div class="flex items-start gap-3">
                <span class="mt-1 inline-flex h-8 w-8 items-center justify-center rounded-full bg-[#C9A227] text-white">
                    <i class="fas fa-check"></i>
                </span>
                <div>
                    <p class="font-semibold">Berhasil</p>
                    <p class="text-sm">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    <x-carousel />

    {{-- INFO PENCARIAN (Berdiri sendiri) --}}
    @if(request('search'))
        <div class="mb-6 flex items-center justify-between bg-gray-50 border border-gray-200 p-4 rounded-lg">
            <p class="text-gray-700">
                Menampilkan hasil pencarian untuk: <span class="font-bold text-gray-900">"{{ request('search') }}"</span>
            </p>
            <a href="{{ route('home') }}" class="text-sm text-red-500 hover:text-red-700 font-medium">
                <i class="fas fa-times mr-1"></i> Hapus Filter
            </a>
        </div>
    @endif

    {{-- KONDISI PRODUK KOSONG ATAU ADA --}}
    @if($products->isEmpty())
        <div class="w-full py-12 flex flex-col items-center justify-center text-center">
            <i class="fas fa-box-open text-6xl text-gray-300 mb-4"></i>
            <h3 class="text-xl font-bold text-gray-700">Produk tidak ditemukan</h3>
            <p class="text-gray-500 mt-2">Coba gunakan kata kunci lain atau hapus filter pencarian.</p>
        </div>
    @else
        {{-- INI ADALAH GRID SATU-SATUNYA (Berdiri sendiri) --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
            @foreach($products as $product)
                <x-product-card :title="$product->title" :price="$product->lowest_price" :rating="$product->rating"
                    :sold="number_format($product->sold, 0, ',', '.')" :location="$product->location" :image="$product->image"
                    :url="route('product.detail', ['id' => $product->id])" :product-id="$product->id" />
            @endforeach
        </div>
    @endif

@endsection