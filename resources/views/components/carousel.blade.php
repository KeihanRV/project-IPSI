<div class="w-full border border-yellow-400 p-1 mb-8 bg-gray-50 rounded-lg shadow-sm">
    <div class="swiper mySwiper rounded-md overflow-hidden relative">
        <div class="swiper-wrapper">
            
        <div class="swiper-slide w-full h-[300px] md:h-[400px] flex flex-col items-center justify-center relative overflow-hidden">
            <img src="{{ asset('carousel/promo.jpg') }}" alt="promo" class="absolute inset-0 object-cover w-full h-full z-0">
            <div class="absolute inset-0 bg-black/40 z-0"></div>
            <h2 class="text-3xl md:text-5xl font-bold text-white z-10 text-center drop-shadow-lg px-4">
                Promo Spesial Hari Ini!
            </h2>
            <div class="absolute bottom-0 w-3/4 h-1/2 bg-gray-300 opacity-30 clip-path-mountain z-0"></div>
        </div>

            <div class="swiper-slide w-full h-[300px] md:h-[400px] bg-brand flex flex-col items-center justify-center relative">
                <h2 class="text-4xl font-bold text-white z-10 mb-2">Diskon Hingga 50%</h2>
                <p class="text-yellow-200 z-10 text-lg">Untuk Koleksi Sepatu Terbaru</p>
            </div>

            <div class="swiper-slide w-full h-[300px] md:h-[400px] bg-gray-800 flex flex-col items-center justify-center relative">
                <h2 class="text-4xl font-bold text-white z-10 mb-2">Gratis Ongkir</h2>
                <p class="text-gray-300 z-10 text-lg">Ke Seluruh Indonesia Raya</p>
            </div>

        </div>

        <div class="swiper-button-next !text-brand drop-shadow-md"></div>
        <div class="swiper-button-prev !text-brand drop-shadow-md"></div>

        <div class="swiper-pagination"></div>
    </div>
</div>

<style>
    .clip-path-mountain {
        clip-path: polygon(0 100%, 30% 20%, 50% 60%, 80% 10%, 100% 100%);
    }
    /* Kustomisasi warna pagination agar sesuai tema */
    .swiper-pagination-bullet-active {
        background-color: #7A702B !important; 
    }
</style>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var swiper = new Swiper(".mySwiper", {
            loop: true, // Berputar terus menerus
            grabCursor: true, // Kursor berubah jadi tangan saat di-hover
            
            // Konfigurasi Autoplay
            autoplay: {
                delay: 4000, 
                disableOnInteraction: false, // Tetap autoplay setelah user klik/geser manual
            },
            
            // Tombol Kiri-Kanan
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },
            
            // Indikator Titik di bawah
            pagination: {
                el: ".swiper-pagination",
                clickable: true,
            },
        });
    });
</script>
@endpush