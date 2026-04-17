@extends('layouts.app')

@section('content')
    <div class="max-w-6xl mx-auto px-4 py-8">
        <div class="mb-8">
            <nav class="flex items-center text-sm text-gray-600 mb-4">
                <a href="{{ route('home') }}" class="hover:text-brand">
                    <i class="fas fa-home mr-1"></i> Beranda
                </a>
                <i class="fas fa-chevron-right mx-2"></i>
                <span class="font-medium text-gray-900">Keranjang Belanja</span>
            </nav>
            <h1 class="text-3xl font-bold text-gray-900">Keranjang Belanja</h1>
            @if(session('success'))
                <div class="mt-4 p-4 bg-green-50 border border-green-200 rounded-lg">
                    <p class="text-green-800">{{ session('success') }}</p>
                </div>
            @endif
        </div>

        @php
            $cartService = new \App\Services\CartService();
            $items = $cartService->getItems();
            $total = $cartService->total();
            $count = $cartService->count();
        @endphp

        @if(empty($items))
            <div class="text-center py-20">
                <i class="fas fa-shopping-cart text-8xl text-gray-300 mb-6"></i>
                <h3 class="text-2xl font-bold text-gray-700 mb-2">Keranjang kosong</h3>
                <p class="text-gray-500 mb-6">Belum ada produk di keranjang Anda.</p>
                <a href="{{ route('home') }}"
                    class="bg-brand text-white px-8 py-3 rounded-full font-medium hover:bg-opacity-90 transition">
                    Mulai Belanja
                </a>
            </div>
        @else
            <div class="grid lg:grid-cols-3 gap-8">
                {{-- Cart Items --}}
                <div class="lg:col-span-2">
                    @foreach($items as $id => $item)
                        <div class="bg-white border border-gray-200 rounded-xl p-6 mb-4">
                            <div class="flex gap-4">
                                <div
                                    class="w-24 h-24 bg-gray-100 rounded-lg flex items-center justify-center overflow-hidden flex-shrink-0">
                                    @if($item['product']->image)
                                        <img src="{{ $item['product']->image }}" alt="{{ $item['product']->title }}"
                                            class="w-full h-full object-cover">
                                    @else
                                        <i class="fas fa-image text-2xl text-gray-400"></i>
                                    @endif
                                </div>
                                <div class="flex-grow min-w-0">
                                    <h3 class="font-bold text-lg text-gray-900 mb-1 line-clamp-1">{{ $item['product']->title }}</h3>
                                    <p class="text-2xl font-bold text-brand font-courier mb-4">
                                        Rp{{ number_format($item['price'], 0, ',', '.') }}</p>

                                    <div class="flex items-center gap-3">
                                        <label class="text-sm font-medium text-gray-700 w-20">Jumlah:</label>
                                        <form action="{{ route('cart.update') }}" method="POST" class="flex items-center gap-2">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="updates[0][product_id]" value="{{ $id }}">
                                            <div class="flex border border-gray-300 rounded-lg overflow-hidden">
                                                <button type="button" onclick="this.parentElement.previousElementSibling.stepDown()"
                                                    class="w-10 h-10 flex items-center justify-center hover:bg-gray-100">-</button>
                                                <input type="number" name="updates[0][qty]" value="{{ $item['qty'] }}" min="0"
                                                    max="99" class="w-20 h-10 text-center border-0 focus:ring-0">
                                                <button type="button" onclick="this.parentElement.previousElementSibling.stepUp()"
                                                    class="w-10 h-10 flex items-center justify-center hover:bg-gray-100">+</button>
                                            </div>
                                            <x-primary-button type="submit" size="sm">
                                                Update
                                            </x-primary-button>
                                        </form>
                                    </div>
                                </div>
                                <div class="flex flex-col items-end gap-2 flex-shrink-0">
                                    <form action="{{ route('cart.remove', $id) }}" method="POST" class="inline"
                                        onsubmit="return confirm('Hapus item ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <x-danger-button size="sm" type="submit">
                                            <i class="fas fa-trash"></i>
                                        </x-danger-button>
                                    </form>
                                    <span
                                        class="font-bold text-xl text-gray-900">Rp{{ number_format($item['qty'] * $item['price'], 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Summary --}}
                <div class="bg-white border border-gray-200 rounded-xl p-6 h-fit sticky top-8">
                    <h3 class="text-xl font-bold text-gray-900 mb-6">Ringkasan</h3>
                    <div class="space-y-3 mb-6">
                        <div class="flex justify-between text-lg">
                            <span>Total Item:</span>
                            <span>{{ $count }} {{ Str::plural('produk', $count) }}</span>
                        </div>
                        <div class="flex justify-between text-2xl font-bold text-gray-900">
                            <span>Total Harga:</span>
                            <span>Rp{{ number_format($total, 0, ',', '.') }}</span>
                        </div>
                    </div>
                    <div class="space-y-3">
                        <x-primary-button class="w-full py-3 text-lg" onclick="alert('Checkout akan diimplementasi nanti!')">
                            <i class="fas fa-credit-card mr-2"></i> Checkout
                        </x-primary-button>
                        <form action="{{ route('cart.clear') }}" method="POST" class="w-full text-center"
                            onsubmit="return confirm('Kosongkan keranjang?')">
                            @csrf
                            @method('DELETE')
                            <x-secondary-button class="w-full py-3 text-lg">
                                Kosongkan Keranjang
                            </x-secondary-button>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                // Qty stepper
                document.querySelectorAll('input[type=number]').forEach(input => {
                    input.addEventListener('input', function () {
                        if (this.value < 0) this.value = 0;
                        if (this.value > 99) this.value = 99;
                    });
                });
            });
        </script>
    @endpush
@endsection