@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-bold text-[11px] text-[#584C08] mb-1 pl-2 tracking-tighter']) }}>
    {{ $value ?? $slot }}
</label>