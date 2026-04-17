@props(['name', 'label', 'type' => 'text', 'placeholder' => ''])

<div class="mb-4">
    @if($label)
        <label for="{{ $name }}" class="block text-xs text-gray-500 mb-1">{{ $label }}</label>
    @endif
    <input type="{{ $type }}" name="{{ $name }}" id="{{ $name }}" placeholder="{{ $placeholder }}"
        class="w-full bg-transparent border-b border-gray-400 focus:border-brand focus:ring-0 px-0 py-1 text-sm text-gray-800 outline-none transition-colors"
        {{ $attributes }}>
    @error($name) <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
</div>