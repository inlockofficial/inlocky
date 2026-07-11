<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-white">
            My Requests
        </h2>
    </x-slot>

    @php
        $sections = [
            'priced' => 'Quote Ready',
            'pending' => 'Pending Review',
            'converted' => 'Converted to Order',
            'expired' => 'Expired',
            'rejected' => 'Rejected',
        ];

        $isEmpty = collect($grouped)->every(fn ($group) => $group->isEmpty());
    @endphp

    <div class="px-4 py-6 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-7xl space-y-8">

            @if($isEmpty)
                <div class="rounded-2xl border border-[#242833] bg-[#171a21] p-4 shadow-xl sm:p-6">
                    <x-empty-state
                        title="No requests yet"
                        message="Paste an AliExpress link from your dashboard to get your first DZD price."
                        :actionHref="route('dashboard')"
                        actionLabel="Go to Dashboard" />
                </div>
            @endif

            @foreach($sections as $key => $title)
                @continue($grouped[$key]->isEmpty())

                <div>
                    <h3 class="mb-4 text-lg font-bold text-white">{{ $title }}</h3>

                    <div class="space-y-4">
                        @foreach($grouped[$key] as $product)
                            <div class="rounded-xl border border-[#242833] bg-[#171a21] p-4 shadow-xl sm:p-5">
                                <div class="flex gap-4">
                                    <div class="shrink-0">
                                        @if($product->image)
                                            <img src="{{ str_starts_with($product->image, 'http') ? $product->image : asset('storage/'.$product->image) }}"
                                                 alt="{{ $product->title ?? 'Product' }}"
                                                 class="h-16 w-16 rounded-lg border border-[#242833] object-cover sm:h-20 sm:w-20"
                                                 loading="lazy">
                                        @else
                                            <div class="flex h-16 w-16 items-center justify-center rounded-lg bg-[#0f1115] text-xs text-gray-500 sm:h-20 sm:w-20">
                                                No image
                                            </div>
                                        @endif
                                    </div>

                                    <div class="min-w-0 flex-1">
                                        <div class="flex flex-col gap-2 sm:flex-row sm:items-start sm:justify-between">
                                            <div class="min-w-0">
                                                <h4 class="truncate text-base font-bold text-white">
                                                    {{ $product->title ?: 'Awaiting price from our team' }}
                                                </h4>
                                                <p class="mt-1 text-xs text-gray-500">
                                                    Request #{{ $product->id }} &middot; {{ $product->created_at?->format('M d, Y') }}
                                                </p>
                                            </div>

                                            <x-status-badge :status="($key === 'expired') ? 'expired' : $product->status" />
                                        </div>

                                        @if($product->final_price_dzd)
                                            <p class="mt-3 text-lg font-black text-[#e9c38c]">
                                                {{ number_format($product->final_price_dzd, 2) }} DZD
                                            </p>
                                        @endif

                                        @if($key === 'converted' && $product->order)
                                            <div class="mt-3 rounded-lg border border-[#e9c38c]/30 bg-[#e9c38c]/10 p-3 text-sm text-[#e9c38c]">
                                                This request has been converted into Order #{{ $product->order->id }}.
                                            </div>
                                        @endif

                                        <div class="mt-4">
                                            @if($key === 'converted' && $product->order)
                                                <a href="{{ route('orders.tracking', $product->order) }}"
                                                   class="inline-flex min-h-11 items-center justify-center rounded-lg bg-[#e9c38c] px-5 py-2 text-sm font-black text-[#0b0f19] transition hover:bg-[#f1d5a7]">
                                                    View Order &rarr;
                                                </a>
                                            @elseif($key === 'priced')
                                                <a href="{{ route('request.view', $product->id) }}"
                                                   class="inline-flex min-h-11 items-center justify-center rounded-lg bg-[#e9c38c] px-5 py-2 text-sm font-black text-[#0b0f19] transition hover:bg-[#f1d5a7]">
                                                    View Quote
                                                </a>
                                            @elseif($key === 'pending')
                                                <a href="{{ route('request.waiting', $product->id) }}"
                                                   class="inline-flex min-h-11 items-center justify-center rounded-lg border border-[#242833] px-5 py-2 text-sm font-bold text-gray-300 transition hover:border-[#e9c38c] hover:text-[#e9c38c]">
                                                    View Status
                                                </a>
                                            @elseif($key === 'rejected')
                                                <a href="{{ route('request.rejected', $product->id) }}"
                                                   class="inline-flex min-h-11 items-center justify-center rounded-lg border border-red-500/40 px-5 py-2 text-sm font-bold text-red-300 transition hover:bg-red-500/10">
                                                    View Details
                                                </a>
                                            @elseif($key === 'expired')
                                                <a href="{{ route('dashboard') }}"
                                                   class="inline-flex min-h-11 items-center justify-center rounded-lg border border-orange-500/40 px-5 py-2 text-sm font-bold text-orange-300 transition hover:bg-orange-500/10">
                                                    Request New Quote
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach

        </div>
    </div>
</x-app-layout>
