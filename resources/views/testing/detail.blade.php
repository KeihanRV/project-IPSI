@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
    <div class="flex flex-col md:flex-row gap-8">
        <div class="w-full md:w-1/2 aspect-square bg-gray-100 rounded-xl flex items-center justify-center">
            @if ($product->image)
                <img src="{{ asset( $product->image) }}" alt="{{ $product->title }}" class="w-full h-full object-cover">
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
                    Ini adalah deskripsi produk dummy untuk ID {{ $product->id }}. Di sini Anda bisa menambahkan detail spesifikasi, keunggulan produk, dan informasi lainnya.
                </p>
            </div>

            <x-button label="Tambah ke Keranjang" type="primary" class="w-full py-3 text-lg" />
        </div>
    </div>
</div>
@endsection