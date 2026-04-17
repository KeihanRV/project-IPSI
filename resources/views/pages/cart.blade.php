@extends('layouts.app')

@section('content')
    <div class="max-w-6xl mx-auto px-4 py-8">
        <div class="flex items-center justify-between mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Keranjang Belanja</h1>
            <a href="{{ route('home') }}" class="text-brand hover:underline font-medium">
                ← Lanjut Belanja
            </a>
        </div>

        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-6">
                {{ session('success') }}
            </div>
        @endif

        @if(empty($items))
            <div class="text-center py-16">
                <i class="fas fa-shopping-cart text-6xl text-gray-300 mb-4"></i>
                <h3 class="text-xl font-bold text-gray-700 mb-2">Keranjang kosong</h3>
                <p class="text-gray-500 mb-6">Belum ada produk di keranjang.</p>
                <a href="{{ route('home') }}"
                    class="bg-brand text-white px-8 py-3 rounded-full font-medium hover:bg-opacity-90 transition">
                    Mulai Belanja
                </a>
            </div>
        @else
            <div class="bg-white rounded-xl shadow-sm border overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-12">
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Produk</th>
                                <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Harga</th>
                                <th class="px-6 py-4 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Qty
                                </th>
                                <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Subtotal</th>
                                <th
                                    class="px-6 py-4 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-16">
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($items as $id => $item)
                                <tr>
                                    <td class="px-6 py-4">
                                        <img src="{{ $item['image'] ?: asset('images/no-image.jpg') }}" alt="{{ $item['name'] }}"
                                            class="w-16 h-16 object-cover rounded">
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="font-medium text-gray-900">{{ $item['name'] }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="text-lg font-bold font-courier">
                                            Rp{{ number_format($item['price'], 0, ',', '.') }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <form method="PATCH" action="{{ route('cart.update') }}" class="inline">
                                            <input type="hidden" name="items[{{ $id }}][id]" value="{{ $id }}">
                                            <input type="number" name="items[{{ $id }}][qty]" value="{{ $item['qty'] }}" min="1"
                                                class="w-20 h-10 border border-gray-300 rounded-lg text-center focus:ring-2 focus:ring-brand">
                                            <button type="submit"
                                                class="ml-2 text-brand hover:text-brand-dark text-sm font-medium">Update</button>
                                        </form>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="text-lg font-bold font-courier">
                                            Rp{{ number_format($item['qty'] * $item['price'], 0, ',', '.') }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-center">

                                        <a href="{{ route('cart.remove', $id) }}" onclick="return confirm('Hapus item ini?')"
                                            class="text-red-500 hover:text-red-700 text-sm font-medium">
                                            <i class="fas fa-trash"></i>
                                        </a>

                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div
                class="mt-8 flex flex-col md:flex-row justify-between items-end gap-6 bg-white p-6 rounded-xl shadow-sm border">
                <div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Total ({{ count($items) }} item)</h3>
                    <p class="text-2xl font-bold text-gray-900 font-courier">Rp{{ number_format($total, 0, ',', '.') }}</p>
                </div>
                <div class="flex flex-col sm:flex-row gap-3">
                    <form method="DELETE" action="{{ route('cart.clear') }}"
                        onsubmit="return confirm('Kosongkan seluruh keranjang?')" class="order-2">
                        @csrf
                        @method('DELETE')
                        <x-button type="secondary" label="Kosongkan Keranjang" />
                    </form>
                    <x-button type="primary" label="Checkout"
                        class="!w-full sm:w-auto order-1 sm:order-none bg-green-600 hover:bg-green-700" />
                </div>
            </div>
        @endif
    </div>
@endsection