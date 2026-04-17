@props(['name', 'label', 'rows' => 3, 'placeholder' => ''])

<div class="mb-4">
    <label for="{{ $name }}" class="block text-xs text-gray-500 mb-1">{{ $label }}</label>
    <textarea name="{{ $name }}" id="{{ $name }}" rows="{{ $rows }}" placeholder="{{ $placeholder }}"
        class="w-full bg-transparent border-b border-gray-400 focus:border-brand focus:ring-0 px-0 py-1 text-sm text-gray-800 outline-none transition-colors resize-none"
        {{ $attributes }}></textarea>
    @error($name) <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
</div>