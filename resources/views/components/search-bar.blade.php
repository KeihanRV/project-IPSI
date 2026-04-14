<form action="{{ route('home') }}" method="GET" class="relative w-full max-w-sm">
    <input 
        type="text" 
        name="search" 
        value="{{ request('search') }}" {{-- Menyimpan teks yang diketik agar tidak hilang setelah disubmit --}}
        placeholder="Search Product" 
        class="w-full bg-search text-gray-700 rounded-full py-2 pl-4 pr-10 focus:outline-none focus:ring-2 focus:ring-brand border border-transparent font-teachers"
    >
    <button type="submit" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-brand transition">
        <i class="fas fa-search"></i>
    </button>
</form>