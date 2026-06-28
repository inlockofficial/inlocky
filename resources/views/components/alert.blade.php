@props([
    'type' => 'info',
    'title' => null,
])

@php
    $classes = [
        'info' => 'border-blue-500/30 bg-blue-500/10 text-blue-100',
        'success' => 'border-green-500/30 bg-green-500/10 text-green-100',
        'warning' => 'border-yellow-500/30 bg-yellow-500/10 text-yellow-100',
        'error' => 'border-red-500/30 bg-red-500/10 text-red-100',
    ];

    $alertClass = $classes[$type] ?? $classes['info'];
@endphp

<div {{ $attributes->merge(['class' => 'rounded-lg border p-4 ' . $alertClass]) }}>
    @if($title)
        <p class="text-sm font-bold">{{ $title }}</p>
    @endif

    <div class="{{ $title ? 'mt-1' : '' }} text-sm leading-relaxed">
        {{ $slot }}
    </div>
</div>
