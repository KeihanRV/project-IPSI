{{-- Tambahkan 'url' ke dalam @props --}}
@props(['title', 'price', 'rating', 'sold', 'location', 'image', 'url' => '#'])

{{-- Ubah div utama menjadi tag <a> --}}
<a href="{{ $url }}" class="border border-gray-300 rounded-xl p-4 flex flex-col hover:shadow-lg transition-all duration-300 bg-white hover:-translate-y-1 block">
    <div class="w-full aspect-square bg-gray-100 rounded-lg flex items-center justify-center mb-4 overflow-hidden">
        @if($image)
            <img src="{{ $image }}" alt="{{ $title }}" class="object-cover w-full h-full">
        @else
            <i class="fas fa-image text-6xl text-gray-300"></i>
        @endif
    </div>

    <div class="flex flex-col flex-grow">
        <h3 class="text-gray-800 font-medium text-sm mb-1 truncate">{{ $title }}</h3>
        <p class="font-bold text-gray-900 text-lg mb-2 font-courier">Rp{{ number_format($price, 0, ',', '.') }}</p>
        
        <div class="mt-auto">
            <div class="flex items-center text-xs text-gray-600 mb-1">
                <i class="fas fa-star text-yellow-400 mr-1"></i>
                <span>{{ number_format($rating, 1, ',', '.') }}</span>
                <span class="mx-1">&bull;</span>
                <span>{{ $sold }} terjual</span>
            </div>
            
            <div class="flex items-center text-xs text-gray-500">
                <i class="fas fa-map-marker-alt mr-1.5"></i>
                <span>{{ $location }}</span>
            </div>
        </div>
    </div>
</a>