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

        @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg mb-6">
                {{ session('error') }}
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
            @php
                $shipping = 20000;
                $grandTotal = $total + $shipping;
            @endphp

            <div class="grid grid-cols-1 xl:grid-cols-[1.75fr_1fr] gap-6">
                <div class="bg-white rounded-xl shadow-sm border overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-[#6B5E2E] text-[#F5EEDC]">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider w-16">
                                    </th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">
                                        Produk</th>
                                    <th class="px-6 py-4 text-right text-xs font-semibold uppercase tracking-wider">
                                        Harga</th>
                                    <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wider">
                                        Kuantitas</th>
                                    <th class="px-6 py-4 text-right text-xs font-semibold uppercase tracking-wider">
                                        Subtotal</th>
                                    <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wider w-16">
                                        Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-[#F5EEDC] divide-y divide-[#DAD2B0]">
                                @foreach($items as $id => $item)
                                    <tr>
                                        <td class="px-6 py-4">
                                            <img src="{{ $item['image'] ?: asset('images/no-image.jpg') }}" alt="{{ $item['name'] }}"
                                                class="w-16 h-16 object-cover rounded-lg border border-[#DAD2B0]">
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="font-semibold text-[#3F3B27]">{{ $item['name'] }}</div>
                                            @if(!empty($item['variant']))
                                                <div class="text-sm text-[#6B5E2E] mt-1">Varian: {{ $item['variant'] }}</div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <div class="text-lg font-bold font-courier text-[#3F3B27]">
                                                Rp{{ number_format($item['price'], 0, ',', '.') }}</div>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <div class="inline-flex items-center rounded-lg border border-[#DAD2B0] bg-[#F5EEDC] shadow-sm">
                                                <form method="POST" action="{{ route('cart.update', $id) }}">
                                                    @csrf
                                                    <input type="hidden" name="qty" value="{{ max($item['qty'] - 1, 1) }}">
                                                    <button type="submit"
                                                        class="px-3 py-2 font-bold text-[#6B5E2E] hover:text-white hover:bg-[#6B5E2E] transition rounded-l-lg">
                                                        -
                                                    </button>
                                                </form>

                                                <span class="px-4 text-sm font-semibold text-[#3F3B27]">{{ $item['qty'] }}</span>

                                                <form method="POST" action="{{ route('cart.update', $id) }}">
                                                    @csrf
                                                    <input type="hidden" name="qty" value="{{ $item['qty'] + 1 }}">
                                                    <button type="submit"
                                                        class="px-3 py-2 font-bold text-[#6B5E2E] hover:text-white hover:bg-[#6B5E2E] transition rounded-r-lg">
                                                        +
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <div class="text-lg font-bold font-courier text-[#3F3B27]">
                                                Rp{{ number_format($item['subtotal'], 0, ',', '.') }}</div>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <form method="POST" action="{{ route('cart.destroy', $id) }}" onsubmit="return confirm('Hapus item ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-[#6B5E2E] hover:text-red-600">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-6 p-6 border-t border-[#DAD2B0] bg-[#F5EEDC]">
                        <label for="note" class="block text-sm font-semibold text-[#6B5E2E] mb-2">Catatan</label>
                        <textarea id="note" rows="5" class="w-full rounded-xl border border-[#DAD2B0] bg-white px-4 py-3 text-sm text-[#3F3B27] focus:outline-none focus:ring-2 focus:ring-[#C9A227]"
                            placeholder="Tambahkan catatan khusus untuk pesanan..."></textarea>
                    </div>
                </div>

                <aside class="bg-[#F5EEDC] border border-[#DAD2B0] rounded-xl shadow-sm p-6">
                    <div class="mb-6">
                        <p class="text-xs font-semibold uppercase tracking-wider text-[#6B5E2E] mb-2">Penerima</p>
                        <h2 class="text-xl font-bold text-[#3F3B27]">{{ auth()->user()->name }}</h2>
                    </div>

                    <div class="space-y-4 text-sm text-[#3F3B27]">
                        <div class="rounded-2xl border border-[#DAD2B0] bg-white p-4">
                            <p class="font-semibold text-[#6B5E2E] mb-2">Alamat Pengiriman</p>
                            @php
                                $user = auth()->user();
                                $addressParts = array_filter([
                                    $user->address,
                                    $user->district,
                                    $user->city,
                                    $user->province,
                                    $user->postal_code
                                ]);
                                $fullAddress = implode(', ', $addressParts);
                            @endphp
                            <p>{{ $fullAddress ?: 'Alamat belum diisi' }}</p>
                        </div>

                        <div class="rounded-2xl border border-[#DAD2B0] bg-white p-4">
                            <p class="font-semibold text-[#6B5E2E] mb-2">Estimasi Tiba</p>
                            <p>2 - 3 hari kerja</p>
                        </div>
                    </div>

                    <div class="mt-6 border-t border-[#DAD2B0] pt-4 space-y-3 text-sm text-[#3F3B27]">
                        <div class="flex justify-between">
                            <span>Subtotal</span>
                            <span>Rp{{ number_format($total, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Estimasi Ongkir</span>
                            <span>Rp{{ number_format($shipping, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between font-semibold text-[#3F3B27]">
                            <span>Total</span>
                            <span>Rp{{ number_format($grandTotal, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('cart.checkout') }}" class="mt-6">
                        @csrf
                        <button type="submit" class="w-full rounded-xl bg-[#C9A227] text-white py-3 font-semibold uppercase tracking-wider hover:bg-[#B08B1E] transition">
                            Checkout
                        </button>
                    </form>
                </aside>
            </div>
        @endif
    </div>
@endsection