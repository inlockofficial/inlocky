@props([
    'title',
    'message' => null,
    'actionHref' => null,
    'actionLabel' => null,
])

<div {{ $attributes->merge(['class' => 'rounded-lg border border-dashed border-[#242833] bg-[#111827] px-5 py-10 text-center']) }}>
    <p class="text-base font-bold text-white">{{ $title }}</p>

    @if($message)
        <p class="mx-auto mt-2 max-w-md text-sm leading-relaxed text-gray-400">
            {{ $message }}
        </p>
    @endif

    @if($actionHref && $actionLabel)
        <a href="{{ $actionHref }}"
           class="mt-5 inline-flex min-h-11 items-center justify-center rounded-lg bg-[#e9c38c] px-5 py-3 text-sm font-black text-[#0b0f19] transition hover:bg-[#f1d5a7]">
            {{ $actionLabel }}
        </a>
    @endif
</div>
