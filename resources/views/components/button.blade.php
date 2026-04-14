@props(['type' => 'primary', 'label' => 'Button', 'href' => null])

@php
    $classes = 'px-4 py-2 rounded-md font-medium text-sm transition-colors duration-200 ';
    
    if ($type === 'primary') {
        $classes .= 'bg-brand text-white hover:bg-yellow-700';
    } elseif ($type === 'secondary') {
        $classes .= 'bg-gray-200 text-gray-700 hover:bg-gray-300';
    } else {
        $classes .= 'border border-gray-300 text-gray-700 hover:bg-gray-50';
    }
@endphp

@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $label }}
    </a>
@else
    <button {{ $attributes->merge(['class' => $classes]) }}>
        {{ $label }}
    </button>
@endif