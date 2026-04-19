<div class="flex justify-center w-full mt-5">
    <button {{ $attributes->merge(['class' => 'inline-flex items-center justify-center w-2/3 px-5 py-2.5 bg-[#D3B514] rounded-full font-bold text-sm text-[#584C08] hover:bg-[#b89d0f] transition shadow-lg']) }}>
        {{ $slot }}
    </button>
</div>