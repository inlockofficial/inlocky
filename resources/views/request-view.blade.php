@php
    $quoteExpired = $product->quote_expires_at && $product->quote_expires_at->isPast();
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>INLOCK - Product Details</title>

    @vite(['resources/css/app.css','resources/js/app.js'])
</head>

<body class="min-h-screen bg-[#0b0f19] px-4 py-5 text-white md:px-8">
    <main class="mx-auto w-full max-w-5xl">
        <div class="mb-5 flex items-center justify-between gap-3">
            <div>
                <p class="text-xl font-black text-[#e9c38c]">INLOCK</p>
                <p class="text-sm text-gray-500">Product quote</p>
            </div>

            <x-status-badge :status="$quoteExpired ? 'expired' : $product->status" />
        </div>

        @if($quoteExpired)
            <x-alert type="warning" title="Quote expired" class="mb-5">
                This quote is no longer valid because exchange rates and supplier prices can change. Please submit a new request.
            </x-alert>
        @elseif($product->quote_expires_at)
            <x-alert type="info" title="Quote validity" class="mb-5">
                This quote expires {{ $product->quote_expires_at->diffForHumans() }} on {{ $product->quote_expires_at->format('M d, Y H:i') }}.
            </x-alert>
        @endif

        <section class="overflow-hidden rounded-2xl border border-[#242833] bg-[#171a21] shadow-xl">
            <div class="grid gap-0 md:grid-cols-12">
                <div class="bg-[#0f1115] p-4 md:col-span-5 md:p-6">
                    <div class="overflow-hidden rounded-xl border border-[#242833] bg-[#0b0f19]">
                        <img src="{{ str_starts_with($product->image, 'http') ? $product->image : asset('storage/'.$product->image) }}"
                             alt="{{ $product->title }}"
                             class="aspect-square w-full object-contain"
                             loading="lazy">
                    </div>

                    @if($product->screenshot)
                        <div class="mt-4 rounded-xl border border-[#242833] bg-[#111827] p-3">
                            <p class="text-xs font-bold uppercase tracking-wide text-gray-500">Reference screenshot</p>
                            <img src="{{ asset('storage/'.$product->screenshot) }}"
                                 alt="Reference screenshot"
                                 class="mt-3 w-full rounded-lg border border-[#242833] object-contain">
                        </div>
                    @endif
                </div>

                <div class="p-5 md:col-span-7 md:p-8">
                    <h1 class="text-2xl font-black leading-tight text-white md:text-3xl">
                        {{ $product->title }}
                    </h1>

                    <div class="mt-5 rounded-xl border border-[#e9c38c]/30 bg-[#e9c38c]/10 p-5">
                        <p class="text-xs font-bold uppercase tracking-wide text-[#e9c38c]">Final price</p>
                        <div class="mt-2 flex flex-wrap items-end gap-2">
                            <span class="text-4xl font-black text-[#e9c38c]">
                                {{ number_format($product->final_price_dzd, 2) }}
                            </span>
                            <span class="pb-1 text-base font-bold text-[#e9c38c]/80">DZD</span>
                        </div>

                        @if($product->service_fee_dzd !== null)
                            <p class="mt-3 text-xs text-gray-400">
                                Includes service fee: {{ number_format($product->service_fee_dzd, 2) }} DZD
                            </p>
                        @endif
                    </div>

                    <div class="mt-5 grid grid-cols-2 gap-3">
                        <div class="rounded-xl border border-[#242833] bg-[#0f1115] p-4">
                            <p class="text-xs font-bold uppercase tracking-wide text-gray-500">Quantity</p>
                            <p class="mt-1 text-lg font-bold text-white">{{ $product->quantity }}</p>
                        </div>

                        <div class="rounded-xl border border-[#242833] bg-[#0f1115] p-4">
                            <p class="text-xs font-bold uppercase tracking-wide text-gray-500">Color</p>
                            <p class="mt-1 text-lg font-bold text-white">{{ $product->color ?: 'Not set' }}</p>
                        </div>

                        <div class="rounded-xl border border-[#242833] bg-[#0f1115] p-4">
                            <p class="text-xs font-bold uppercase tracking-wide text-gray-500">Size</p>
                            <p class="mt-1 text-lg font-bold text-white">{{ $product->size ?: 'Not set' }}</p>
                        </div>

                        <div class="rounded-xl border border-[#242833] bg-[#0f1115] p-4">
                            <p class="text-xs font-bold uppercase tracking-wide text-gray-500">Rate</p>
                            <p class="mt-1 text-lg font-bold text-white">{{ $product->rate_used ?: config('app.usd_to_dzd') }}</p>
                        </div>
                    </div>

                    @if($product->custom_note)
                        <div class="mt-5 rounded-xl border border-[#242833] bg-[#0f1115] p-4">
                            <p class="text-xs font-bold uppercase tracking-wide text-gray-500">Your note</p>
                            <p class="mt-2 whitespace-pre-line text-sm leading-relaxed text-gray-300">{{ $product->custom_note }}</p>
                        </div>
                    @endif

                    <div class="mt-6 grid gap-3 sm:grid-cols-2">
                        @if($product->status == 'priced' && !$quoteExpired)
                            @auth
                                <form action="{{ route('orders.store') }}" method="POST" class="sm:col-span-2">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <button type="submit"
                                            class="flex min-h-12 w-full items-center justify-center rounded-xl bg-[#e9c38c] px-5 py-3 text-base font-black text-[#0b0f19] transition hover:bg-[#f1d5a7] active:scale-[0.99]">
                                        Buy product
                                    </button>
                                </form>
                            @else
                                <a href="{{ route('login') }}"
                                   class="sm:col-span-2 flex min-h-12 items-center justify-center rounded-xl bg-[#e9c38c] px-5 py-3 text-base font-black text-[#0b0f19] transition hover:bg-[#f1d5a7]">
                                    Log in to purchase
                                </a>
                            @endauth
                        @elseif($quoteExpired)
                            <a href="{{ route('welcome') }}"
                               class="sm:col-span-2 flex min-h-12 items-center justify-center rounded-xl bg-[#e9c38c] px-5 py-3 text-base font-black text-[#0b0f19] transition hover:bg-[#f1d5a7]">
                                Request a new quote
                            </a>
                        @else
                            <button class="sm:col-span-2 min-h-12 rounded-xl border border-dashed border-[#242833] bg-[#0f1115] px-5 py-3 font-bold text-gray-500" disabled>
                                Review in progress
                            </button>
                        @endif

                        <a href="{{ route('welcome') }}"
                           class="flex min-h-11 items-center justify-center rounded-xl border border-[#242833] px-5 py-3 text-sm font-bold text-gray-300 transition hover:border-[#e9c38c] hover:text-[#e9c38c] sm:col-span-2">
                            Back to store
                        </a>
                    </div>
                </div>
            </div>
        </section>
    </main>
</body>
</html>
