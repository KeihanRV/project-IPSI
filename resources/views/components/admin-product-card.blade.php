@props(['product'])

<div class="relative group border border-gray-300 rounded-xl hover:shadow-lg transition-all duration-300 bg-white h-full w-full overflow-hidden">
    
    <a href="{{ route('product.detail', $product->id) }}" class="block p-4 h-full">
        <div class="aspect-square bg-gray-100 rounded-lg mb-4 overflow-hidden flex items-center justify-center border border-gray-200">
            @if($product->image && $product->image !== 'placeholder.png')
                <img src="{{ asset('storage/product/' . $product->image) }}" alt="{{ $product->title }}" class="w-full h-full object-cover">
            @else
                <i class="fas fa-image text-6xl text-gray-300"></i>
            @endif
        </div>

        <div class="flex-1 flex flex-col">
            <h3 class="text-gray-800 font-medium text-sm mb-1 pr-10 truncate">{{ $product->title }}</h3>
            
            <p class="font-bold text-gray-900 text-lg mb-2 font-courier">
                Rp{{ number_format($product->lowest_price, 0, ',', '.') }}
            </p>

            <div class="flex items-center text-xs text-gray-600 mb-3">
                <i class="fas fa-star text-yellow-400 mr-1"></i>
                <span>{{ $product->rating ?? '0' }}</span>
                <span class="mx-1 text-gray-400">|</span>
                <span>{{ number_format($product->sold ?? 0, 0, ',', '.') }} terjual</span>
            </div>

            <div class="mt-auto pt-2">
                <div class="flex items-center text-xs text-gray-500">
                    <i class="fas fa-map-marker-alt mr-1"></i>
                    <span class="truncate">{{ $product->location }}</span>
                </div>
            </div>
        </div>
    </a>

    <div class="absolute top-4 right-4 z-10">
        <x-action-button type="delete" url="{{ route('admin.products.destroy', $product->id) }}" :productName="$product->title" />
    </div>

    <div class="absolute bottom-4 right-4 z-10">
        <x-action-button type="edit" url="{{ route('admin.products.edit', $product->id) }}" />
    </div>
</div>