@props([
    'value' => 0,
    'max' => 100,
    'label' => null,
])

@php
    $percentage = $max > 0 ? min(100, max(0, ($value / $max) * 100)) : 0;
@endphp

<div {{ $attributes }}>
    @if($label)
        <div class="mb-2 flex items-center justify-between text-xs font-bold text-gray-400">
            <span>{{ $label }}</span>
            <span>{{ round($percentage) }}%</span>
        </div>
    @endif

    <div class="h-2 overflow-hidden rounded-full bg-[#242833]">
        <div class="h-full rounded-full bg-[#e9c38c] transition-all duration-500" style="width: {{ $percentage }}%"></div>
    </div>
</div>
