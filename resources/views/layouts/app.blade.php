<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SHOO - E-Commerce</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Teachers:wght@400;500;600;700&display=swap" rel="stylesheet">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: '#7A702B',
                        search: '#FFF9DF',
                    },
                    // ✅ TAMBAHKAN: Konfigurasi Font Family
                    fontFamily: {
                        teachers: ['"Teachers"', 'sans-serif'],
                        courier: ['"Courier New"', 'Courier', 'monospace'],
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-white text-gray-800 font-teachers antialiased">

    <x-navbar />

    <main class="container mx-auto px-4 py-6 max-w-6xl">
        {{ $slot ?? '' }}
        @yield('content')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    @stack('scripts')

</body>
</html>