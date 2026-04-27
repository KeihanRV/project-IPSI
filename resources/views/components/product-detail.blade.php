@extends('layouts.app')

@section('content')
    {{-- 1. Outer Wrapper: Background beige dan padding lebar --}}
    <div class="min-h-screen bg-[#FAF9F6] py-12 px-4 md:px-10 lg:px-20">

        {{-- 2. Main Container: Dibuat lebar (max-w-7xl) agar FIT ke layar --}}
        <div class="max-w-7xl mx-auto">

            {{-- SECTION ATAS: HERO PRODUCT (2 Column Grid) --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-start">

                {{-- SISI KIRI: GAMBAR (Dibuat lebar mengikuti kolom) --}}
                <div
                    class="w-full aspect-square bg-[#F3F3F3] rounded-[40px] border border-[#D4B47B]/20 flex items-center justify-center shadow-sm">
                    {{-- Ganti src dengan path gambar aslimu nanti --}}
                    <img src="/path-to-your-image.png" alt="Product Image" class="w-2/3 opacity-20">
                    <div class="absolute text-gray-400 font-bold uppercase tracking-widest">Image Placeholder</div>
                </div>

                {{-- SISI KANAN: DETAIL INFO --}}
                <div class="flex flex-col space-y-8">
                    <div>
                        <h1 class="text-5xl font-bold text-gray-900 leading-tight">Sepatu Sneakers Kasual Premium</h1>
                        <div class="flex items-center justify-between mt-4">
                            <div class="flex items-center space-x-2">
                                <span class="text-[#E9C874] text-xl">★★★★★</span>
                                <span class="text-gray-500">(1.200 ulasan)</span>
                            </div>
                            <span class="text-[#D4B47B] font-semibold">1.200 terjual</span>
                        </div>
                        <p class="text-gray-500 mt-2 flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" />
                            </svg>
                            Jakarta Selatan
                        </p>
                    </div>

                    {{-- HARGA LUXURY (Font Serif & Ukuran Gede) --}}
                    <div class="text-8xl font-serif font-bold text-black py-4">
                        Rp 189.000
                    </div>

                    {{-- VARIAN --}}
                    <div>
                        <p class="font-bold text-lg mb-4">Varian :</p>
                        <div class="grid grid-cols-3 md:grid-cols-5 gap-3">
                            @foreach(['38', '39', '40', '41', '42'] as $size)
                                <button
                                    class="flex flex-col items-center p-3 bg-[#FDFBF2] border border-[#D4B47B]/30 rounded-2xl hover:bg-[#D4B47B] hover:text-white transition group">
                                    <div class="w-6 h-6 bg-gray-300 rounded-full mb-1 group-hover:bg-white/50"></div>
                                    <span class="text-[10px] text-gray-400 group-hover:text-white">Size</span>
                                    <span class="font-bold text-sm">{{ $size }}</span>
                                </button>
                            @endforeach
                        </div>
                    </div>

                    {{-- QUANTITY & STOCK --}}
                    <div class="flex items-center space-x-8 pt-4">
                        <div class="flex items-center border-2 border-gray-200 rounded-full px-6 py-2 bg-white">
                            <button class="text-2xl font-bold">-</button>
                            <input type="text" value="1"
                                class="w-12 text-center font-bold bg-transparent focus:outline-none">
                            <button class="text-2xl font-bold">+</button>
                        </div>
                        <p class="text-gray-400 font-medium">Stok : 330</p>
                    </div>

                    {{-- CTA BUTTONS --}}
                    <div class="flex space-x-4 pt-6">
                        <button
                            class="flex-1 bg-[#D4B47B] text-white py-5 rounded-full font-bold text-lg shadow-xl hover:bg-[#bfa36a] transform hover:-translate-y-1 transition">
                            + Keranjang
                        </button>
                        <button
                            class="flex-1 border-2 border-[#D4B47B] text-[#D4B47B] py-5 rounded-full font-bold text-lg hover:bg-[#D4B47B] hover:text-white transition">
                            Hubungi
                        </button>
                    </div>
                </div>
            </div>

            {{-- SECTION BAWAH: CARDS (Detail, Spek, Review) --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mt-24 border-t border-gray-200 pt-16">
                {{-- Card Detail --}}
                <div class="bg-[#FDFBF2] p-8 rounded-[32px] border border-dashed border-blue-200 shadow-sm">
                    <h3 class="font-bold text-2xl mb-4">Detail Produk</h3>
                    <p class="text-gray-600 leading-relaxed">Produk berkualitas premium dengan desain eksklusif yang
                        dirancang untuk kenyamanan maksimal.</p>
                </div>

                {{-- Card Spek --}}
                <div class="bg-[#FDFBF2] p-8 rounded-[32px] border border-dashed border-blue-200 shadow-sm">
                    <h3 class="font-bold text-2xl mb-4">Spesifikasi</h3>
                    <ul class="text-gray-600 space-y-2 text-sm">
                        <li>• Material: Canvas dan Rubber</li>
                        <li>• Sol: Rubber Anti-Slip</li>
                        <li>• Ketersediaan: All Sizes</li>
                        <li>• Garansi: 1 Tahun</li>
                    </ul>
                </div>

                {{-- Card Review --}}
                <div class="bg-white p-8 rounded-[32px] border border-dashed border-blue-200 shadow-sm">
                    <h3 class="font-bold text-2xl mb-4">Review Teratas</h3>
                    <div class="flex items-center space-x-4 mb-4">
                        <div
                            class="w-12 h-12 bg-indigo-500 rounded-full flex items-center justify-center text-white font-bold">
                            AS</div>
                        <div>
                            <p class="font-bold">Amanda S.</p>
                            <p class="text-xs text-yellow-500">★★★★★</p>
                        </div>
                    </div>
                    <p class="text-gray-500 text-sm italic">"Kualitas sangat bagus, nyaman dipakai. Sangat recommended!"</p>
                </div>
            </div>

        </div>
    </div>
@endsection