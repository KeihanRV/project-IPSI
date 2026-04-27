@extends('layouts.app')

@section('title', $product->title . ' - SHOO')

@section('content')
    <div class='min-h-screen bg-[#FAF9F6]'>
        <div class='max-w-7xl mx-auto px-6 py-12'>
            <form action="{{ route('cart.add') }}" method="POST">
                @csrf
                @php $defaultVariant = $product->variants->sortBy('price')->first(); @endphp
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <input type="hidden" name="variant_id" id="selected-variant-id" value="{{ $defaultVariant->id ?? 0 }}">

                @if(session('error'))
                    <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg mb-6">
                        {{ session('error') }}
                    </div>
                @endif

                <!-- TOP HERO SECTION -->
                <div class='grid grid-cols-1 lg:grid-cols-2 gap-20 items-start'>

                    <!-- LEFT: Image (aspect-square, thin gold border) -->
                    <div
                        class='aspect-square bg-gradient-to-br from-gray-50/50 to-white border border-[#D4B47B]/30 rounded-3xl flex items-center justify-center p-12 shadow-md'>
                        @if($product->image)
                            <img src="{{ asset('storage/product/' . $product->image) }}" alt="{{ $product->title }}"
                                class="w-full h-full object-cover rounded-2xl">
                        @else
                            <i class='fas fa-image text-[12rem] text-gray-300'></i>
                        @endif
                    </div>

                    <!-- RIGHT: Info + Form Elements -->
                    <div class="space-y-8">

                        <!-- Title -->
                        <h1 class="text-[1.95rem] font-semibold text-gray-900 leading-tight">{{ $product->title }}</h1>

                        <!-- Rating/Location -->
                        <div class="space-y-3">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="flex text-yellow-400 text-[1rem]">★★★★★</div>
                                    <span
                                        class="text-[1rem] font-normal text-gray-700">{{ number_format($product->sold ?? 0) }}
                                        ulasan</span>
                                </div>
                                <span class="text-[1rem] font-normal text-orange-500 flex items-center gap-1">
                                    <i class="fas fa-fire"></i> {{ number_format($product->sold ?? 0) }} terjual
                                </span>
                            </div>
                            <div class="flex items-center gap-3 text-[1rem] font-normal text-gray-700">
                                <i class="fas fa-map-marker-alt text-[#D4B47B]"></i>
                                {{ $product->location }}
                            </div>
                        </div>

                        <!-- Price (Large Serif) -->
                        <div id="price-display" class="text-[2.2rem] font-mono font-bold text-black"
                            style="font-family: 'Courier New', Courier, monospace;">Rp
                            {{ number_format($product->lowest_price, 0, ',', '.') }}
                        </div>

                        <!-- Variants (Grid 3 cols) -->
                        <div>
                            <label class="text-[1.6rem] font-semibold mb-6 block">Varian:</label>
                            <div class="grid grid-cols-3 gap-3">
                                @foreach($product->variants->take(5) as $variant)
                                    <button type="button"
                                        onclick="selectVariant({{ $variant->id }}, '{{ $variant->stock ?? "∞" }}', {{ $variant->price }})"
                                        id="btn-variant-{{ $variant->id }}" data-variant-id="{{ $variant->id }}"
                                        data-variant-price="{{ $variant->price }}"
                                        data-variant-name="{{ $variant->name }}"
                                        class="variant-btn p-3 bg-white border border-gray-200 hover:border-[#D4B47B] rounded-2xl transition-all hover:shadow-sm hover:scale-[1.02] flex flex-col items-center">

                                        <div
                                            class="w-12 h-12 bg-gray-200 rounded-xl flex items-center justify-center mb-2 border-2 border-transparent">
                                            @if($variant->image)
                                                <img src="{{ asset('storage/variant/' . $variant->image) }}"
                                                    alt="{{ $variant->name }}" class="w-full h-full rounded-lg object-cover">
                                            @else
                                                <i class="fas fa-circle text-gray-400 text-lg"></i>
                                            @endif
                                        </div>
                                        <span class="font-medium text-[1rem] text-gray-800 text-center">{{ $variant->name }}</span>
                                    </button>
                                @endforeach
                            </div>
                        </div>

                        <!-- Quantity Selector -->
                        <div class="flex items-center justify-between mb-8">
                            <div class="flex items-center gap-3 p-3 bg-white border border-gray-200 rounded-3xl shadow-sm">
                                <button type="button" onclick="changeQty(-1)"
                                    class="w-12 h-12 border border-gray-300 rounded-2xl flex items-center justify-center hover:bg-gray-50 transition font-bold text-[1rem] text-gray-700">-</button>
                                <input type="number" name="quantity" id="qty-input" value="1" min="1"
                                    class="w-16 text-center text-[1.3rem] font-bold bg-transparent border-0 focus:outline-none [-moz-appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none">
                                <button type="button" onclick="changeQty(1)"
                                    class="w-12 h-12 border border-gray-300 rounded-2xl flex items-center justify-center hover:bg-gray-50 transition font-bold text-[1rem] text-gray-700">+</button>
                            </div>
                            <span id="stock-display" class="text-[1rem] font-medium text-gray-700">Stok: ∞</span>

                        </div>

                        <!-- Subtotal (Align Right) -->
                        <div class="flex justify-between items-baseline mb-8">
                            <span class="text-[1.25rem] font-semibold text-gray-700">Subtotal:</span>
                            <span id="subtotal-price" class="text-[1.125rem] font-bold text-gray-900">Rp
                                {{ number_format($product->lowest_price, 0, ',', '.') }}</span>
                        </div>

                        <!-- Action Buttons (Flex row) -->
                        <div class="flex gap-4">
                            <button type="submit"
                                class="flex-1 bg-gradient-to-r from-[#D4B47B] to-[#C8A76A] text-white font-semibold py-3 px-6 rounded-full text-[1.125rem] shadow-lg hover:shadow-xl hover:-translate-y-0.5 transition-all duration-300 flex items-center justify-center gap-2">
                                <i class="fas fa-shopping-cart text-[1.125rem]"></i>
                                + Keranjang
                            </button>
                            <button type="button"
                                class="flex-1 border-2 border-[#D4B47B] text-[#D4B47B] font-semibold py-3 px-6 rounded-full text-[1.125rem] shadow-lg hover:bg-[#D4B47B] hover:text-white transition-all duration-300 flex items-center justify-center gap-2">
                                <i class="fab fa-whatsapp text-[1.125rem]"></i>
                                    <a href="https://wa.me/6281411176027" target="_blank" class="text-[#D4B47B] hover:text-white">Hubungi</a>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- BOTTOM INFO SECTION -->
                <div class='grid grid-cols-1 md:grid-cols-2 gap-6 mt-16 border-t pt-12 border-blue-200'>
                    <!-- Detail Card -->
                    <div
                        class='bg-[#FDFBF2] p-8 rounded-xl border border-dashed border-blue-300 hover:shadow-xl transition-shadow'>
                        <h2 class='text-xl font-bold mb-6 text-gray-900'>Detail Produk</h2>
                        <p class='text-sm leading-relaxed text-gray-700'>
                            {{ $product->description ?: 'Deskripsi lengkap produk berkualitas premium.' }}
                        </p>
                    </div>

                    <!-- Spesifikasi Card -->
                    <div
                        class='bg-[#FDFBF2] p-8 rounded-xl border border-dashed border-blue-300 hover:shadow-xl transition-shadow'>
                        <h2 class='text-xl font-bold mb-6 text-gray-900'>Spesifikasi</h2>
                        @if($product->specification)
                            <ul class='space-y-3 text-sm text-gray-700'>
                                @foreach(explode("\n", $product->specification) as $line)
                                    @if(trim($line))
                                        <li>• {{ trim($line) }}</li>
                                    @endif
                                @endforeach
                            </ul>
                        @else
                            <ul class='space-y-3 text-sm text-gray-700'>
                                <li>• Material Premium</li>
                                <li>• Garansi 30 Hari</li>
                                <li>• Ready Stock</li>
                            </ul>
                        @endif
                    </div>

                    <!-- Review Card -->
                    <!-- <div
                        class='bg-[#FDFBF2] p-8 rounded-xl border border-dashed border-blue-300 hover:shadow-xl transition-shadow'>
                        <h2 class='text-2xl font-bold mb-6 text-gray-900'>Review Teratas</h2>
                        <div class='flex items-start gap-4 mb-6'>
                            <div
                                class='w-16 h-16 bg-gradient-to-r from-purple-500 to-pink-500 rounded-2xl flex items-center justify-center text-white font-bold text-lg flex-shrink-0'>
                                AS
                            </div>
                            <div class='flex-1'>
                                <div class='flex items-center gap-2 mb-2'>
                                    <h3 class='font-bold text-lg text-gray-900'>Amanda S.</h3>
                                    <div class='flex text-yellow-400'>
                                        ★★★★☆
                                    </div>
                                </div>
                                <p class='text-sm text-gray-600 mb-3'>Verified Buyer</p>
                                <p class='text-gray-700 leading-relaxed text-sm'>"Produk sangat berkualitas dan sesuai
                                    ekspektasi!"</p>
                            </div>
                        </div>
                        <div class='grid grid-cols-2 gap-2 pt-4 border-t border-gray-200'>
                            @for($i = 1; $i <= 4; $i++)
                                <div
                                    class='aspect-square bg-gray-200 rounded-lg cursor-pointer hover:shadow-md transition-shadow'>
                                </div>
                            @endfor
                        </div>
                    </div> -->
                </div>
        </div>
        </form>
    </div>

    <script>
        // CSS for active variant button
        const style = document.createElement('style');
        style.textContent = `
                .variant-btn {
                    transition: all 0.2s ease;
                }
                .variant-btn.active {
                    border-color: #D4B47B !important;
                    background-color: #F8F6F0 !important;
                    transform: scale(1.02) !important;
                    box-shadow: 0 4px 12px rgba(212, 180, 141, 0.2) !important;
                }
            `;
        document.head.appendChild(style);

        let currentItemPrice = {{ $product->lowest_price }};

        function formatRupiah(value) {
            return new Intl.NumberFormat('id-ID').format(value);
        }

        function updatePriceDisplay(price) {
            currentItemPrice = price;
            const priceDisplay = document.getElementById('price-display');
            if (priceDisplay) {
                priceDisplay.textContent = 'Rp ' + formatRupiah(price);
            }
        }

        function updateSubtotal() {
            const qty = parseInt(document.getElementById('qty-input').value) || 1;
            const subtotalPrice = document.getElementById('subtotal-price');
            if (subtotalPrice) {
                subtotalPrice.textContent = 'Rp ' + formatRupiah(currentItemPrice * qty);
            }
        }

        function selectVariant(id, stock, price) {

            // Update hidden input
            document.getElementById('selected-variant-id').value = id;

            // Update stock display
            document.getElementById('stock-display').innerHTML = 'Stok: ' + stock;

            // Reset all buttons
            document.querySelectorAll('.variant-btn').forEach(btn => {
                btn.classList.remove('active');
            });

            // Activate selected button
            const activeBtn = document.getElementById('btn-variant-' + id);
            if (activeBtn) {
                activeBtn.classList.add('active');
                const variantPrice = parseInt(activeBtn.dataset.variantPrice) || price || currentItemPrice;
                updatePriceDisplay(variantPrice);
                updateSubtotal();
            }
        }


        function changeQty(amt) {
            const input = document.getElementById('qty-input');
            let val = parseInt(input.value) || 1;
            if ((val + amt) >= 1) {
                input.value = val + amt;
                updateSubtotal();
            }
        }
    </script>
@endsection