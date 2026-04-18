@extends('layouts.app')

@section('title', $product['name'] . ' - SHOO')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            {{-- MAIN PRODUCT SECTION --}}
            <div class="grid grid-cols-1 lg:grid-cols-12 lg:gap-x-8">
                {{-- LEFT COLUMN - IMAGE --}}
                <div class="lg:col-span-7">
                    <div
                        class="aspect-[3/2] bg-gradient-to-br from-gray-100 to-gray-200 border-4 border-amber-500/50 border-dashed rounded-2xl p-12 flex items-center justify-center shadow-xl">
                        <i class="fas fa-shoe-prints text-9xl text-gray-400"></i>
                    </div>
                </div>

                {{-- RIGHT COLUMN - PRODUCT INFO --}}
                <div class="lg:col-span-5 mt-10 lg:mt-0" x-data="productDetail($product)">
                    {{-- Title --}}
                    <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-6 leading-tight">
                        {{ $product['name'] }}
                    </h1>

                    {{-- Divider --}}
                    <div class="w-20 h-px bg-amber-500 mb-8"></div>

                    {{-- Rating Row --}}
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center gap-2">
                            <span class="text-2xl font-bold text-gray-900">{{ $product['rating'] }}</span>
                            <div class="flex text-amber-500">
                                @for ($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star"></i>
                                @endfor
                            </div>
                            <span class="text-lg text-gray-500 ml-2">({{ $product['reviews'] }} ulasan)</span>
                        </div>
                        <span class="text-sm text-gray-500 flex items-center gap-1">
                            <i class="fas fa-fire text-orange-500"></i>
                            {{ $product['sold'] }} terjual
                        </span>
                    </div>

                    {{-- Location --}}
                    <div class="flex items-center gap-3 mb-8 text-xl font-medium">
                        <i class="fas fa-map-marker-alt text-amber-500"></i>
                        {{ $product['location'] }}
                    </div>

                    {{-- Price --}}
                    <div class="text-5xl md:text-6xl font-black text-gray-900 mb-10">
                        Rp {{ number_format($product['price'], 0, ',', '.') }}
                    </div>

                    {{-- Variants --}}
                    <div class="mb-10">
                        <label class="text-lg font-semibold mb-6 block">Varian:</label>
                        <div class="grid grid-cols-3 gap-4">
                            @foreach($product['variants'] as $index => $variant)
                                <button type="button"
                                    class="group flex items-center gap-3 p-4 bg-amber-50 border-2 border-amber-200 hover:border-amber-500 hover:bg-amber-100 rounded-xl transition-all duration-200 flex-shrink-0 h-20"
                                    :class="selectedVariant === '{{ $index }}' ? 'bg-amber-500 border-amber-500 shadow-lg text-white' : ''"
                                    @click="selectVariant('{{ $index }}')">
                                    <div class="w-12 h-12 bg-white/50 rounded-lg flex items-center justify-center shadow-sm">
                                        <i class="fas fa-circle text-sm"
                                            :class="selectedVariant === '{{ $index }}' ? 'text-white' : 'text-gray-400'"></i>
                                    </div>
                                    <span class="font-medium text-sm group-hover:text-gray-900"
                                        :class="selectedVariant === '{{ $index }}' ? 'text-white font-bold' : ''">
                                        {{ $variant }}
                                    </span>
                                </button>
                            @endforeach
                        </div>
                    </div>

                    {{-- Quantity & Stock --}}
                    <div class="flex items-center justify-between mb-8">
                        <div class="flex items-center gap-4 p-4 bg-amber-50 border border-amber-200 rounded-xl">
                            <button type="button" @click="decrement()"
                                class="w-14 h-14 border border-gray-300 rounded-xl flex items-center justify-center hover:bg-gray-100 transition font-bold text-lg"
                                :disabled="quantity <= 1"
                                :class="quantity <= 1 ? 'opacity-50 cursor-not-allowed' : ''">-</button>
                            <span class="text-3xl font-bold text-gray-900 w-20 text-center font-mono"
                                x-text="quantity"></span>
                            <button type="button" @click="increment()"
                                class="w-14 h-14 border border-gray-300 rounded-xl flex items-center justify-center hover:bg-gray-100 transition font-bold text-lg"
                                :disabled="quantity >= {{ $product['stock'] }}"
                                :class="quantity >= {{ $product['stock'] }} ? 'opacity-50 cursor-not-allowed' : ''">+</button>
                        </div>
                        <span class="text-xl font-semibold text-gray-700"
                            x-text="'Stok: ' + {{ $product['stock'] }}"></span>
                    </div>

                    {{-- Subtotal --}}
                    <div class="flex justify-between items-end mb-10 text-2xl">
                        <span class="font-semibold text-gray-700">Subtotal:</span>
                        <span class="font-black text-gray-900" x-text="formatRupiah(subtotal)"></span>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <button
                            class="bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 text-white font-bold py-4 px-8 rounded-2xl text-xl shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all duration-300 w-full">
                            <i class="fas fa-shopping-cart mr-3"></i>+ Keranjang
                        </button>
                        <button
                            class="border-2 border-amber-500 text-amber-600 font-bold py-4 px-8 rounded-2xl text-xl shadow-lg hover:bg-amber-500 hover:text-white transition-all duration-300 w-full">
                            <i class="fas fa-phone mr-3"></i>Hubungi
                        </button>
                    </div>
                </div>
            </div>

            {{-- BOTTOM SECTION - 3 COLUMN --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 lg:gap-8 mt-24">
                {{-- Detail Produk --}}
                <div class="lg:col-span-1 mb-8 lg:mb-0">
                    <div class="bg-white border-l-4 border-amber-500 p-8 rounded-2xl shadow-sm">
                        <h2 class="text-2xl font-bold mb-6 text-gray-900">Detail Produk</h2>
                        <p class="text-lg text-gray-600 leading-relaxed">
                            {{ $product['description'] }}
                        </p>
                    </div>
                </div>

                {{-- Spesifikasi --}}
                <div class="lg:col-span-1 mb-8 lg:mb-0">
                    <div class="bg-white border-l-4 border-amber-500 p-8 rounded-2xl shadow-sm">
                        <h2 class="text-2xl font-bold mb-6 text-gray-900">Spesifikasi</h2>
                        <ul class="space-y-3 text-gray-600">
                            @foreach($product['specs'] as $spec)
                                <li class="flex items-center gap-3">
                                    <i class="fas fa-check text-amber-500 w-5"></i>
                                    <span>{{ $spec }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                {{-- Review Card --}}
                <div class="lg:col-span-1">
                    <div class="bg-white border-l-4 border-amber-500 p-8 rounded-2xl shadow-sm">
                        <div class="flex items-start gap-4 mb-6">
                            <div
                                class="w-16 h-16 bg-gradient-to-br from-purple-400 to-pink-400 rounded-full flex items-center justify-center text-white font-bold text-xl flex-shrink-0 shadow-lg">
                                AS
                            </div>
                            <div>
                                <div class="flex items-center gap-3 mb-2">
                                    <h3 class="font-bold text-lg text-gray-900">Amanda S.</h3>
                                    <div class="flex text-amber-500 text-sm">
                                        <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i
                                            class="fas fa-star"></i><i class="far fa-star"></i>
                                    </div>
                                    <span class="text-sm font-medium text-gray-500 ml-3">100+</span>
                                    <i class="fas fa-heart text-gray-400 hover:text-red-500 cursor-pointer ml-2"></i>
                                </div>
                                <p class="text-sm text-gray-500 mb-4">Varian : Premium</p>
                                <p class="text-gray-700 leading-relaxed">
                                    Supporting line text lorem ipsum dolor sit amet, consectetur adipiscing elit sed do
                                    eiusmod tempor incididunt ut labore.
                                </p>
                            </div>
                        </div>
                        <div class="grid grid-cols-4 gap-3 pt-4">
                            @for ($i = 1; $i <= 4; $i++)
                                <div
                                    class="aspect-square bg-gray-200 rounded-lg flex items-center justify-center cursor-pointer hover:shadow-md transition-shadow">
                                    <i class="fas fa-image text-gray-400 text-lg"></i>
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script>
        function productDetail(product) {
            return {
                quantity: 1,
                selectedVariant: null,
                basePrice: product.price,
                stock: product.stock,
                get subtotal() {
                    return this.quantity * this.basePrice;
                },
                formatRupiah(amount) {
                    return new Intl.NumberFormat('id-ID', {
                        style: 'currency',
                        currency: 'IDR',
                        minimumFractionDigits: 0
                    }).format(amount);
                },
                increment() {
                    if (this.quantity < this.stock) {
                        this.quantity++;
                    }
                },
                decrement() {
                    if (this.quantity > 1) {
                        this.quantity--;
                    }
                },
                selectVariant(index) {
                    this.selectedVariant = this.selectedVariant === index ? null : index;
                }
            }
        }
    </script>
@endsection