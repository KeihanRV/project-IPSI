@extends('layouts.app')

@section('content')

    <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-8 mt-4 gap-4">
        <h1 class="text-3xl md:text-4xl font-bold text-gray-800">Daftar Produk</h1>
        
        <x-button 
            type="primary" 
            label="+ Produk" 
            href="{{ route('admin.products.create') }}" 
            class="rounded-md px-6 shadow-sm" 
        />
    </div>

    @if(empty($products))
        <div class="w-full py-16 flex flex-col items-center justify-center text-center bg-gray-50 rounded-xl border border-gray-200">
            <i class="fas fa-box-open text-6xl text-gray-300 mb-4"></i>
            <h3 class="text-xl font-bold text-gray-700">Belum ada produk</h3>
            <p class="text-gray-500 mt-2 mb-4">Silakan tambah produk baru untuk mulai berjualan.</p>
            <x-button type="primary" label="+ Tambah Produk Sekarang" href="{{ route('admin.products.create') }}" />
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
            @foreach ($products as $product)
                <x-admin-product-card :product="$product" />
            @endforeach
        </div>
    @endif

@endsection