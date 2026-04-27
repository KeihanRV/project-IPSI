@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'font-medium text-sm text-green-700 bg-green-100 p-3 rounded-xl text-center mb-4']) }}>
        {{ $status }}
    </div>
@endif