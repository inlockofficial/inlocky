<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-white">
            My Orders
        </h2>
    </x-slot>

    <div class="px-4 py-6 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-7xl">
            <div class="rounded-2xl border border-[#242833] bg-[#171a21] p-4 shadow-xl sm:p-6">
                <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h3 class="text-lg font-bold text-gray-100">Your orders</h3>
                        <p class="mt-1 text-sm text-gray-500">Track payment, processing, and delivery progress.</p>
                    </div>

                    <a href="{{ route('welcome') }}"
                       class="flex min-h-11 items-center justify-center rounded-lg bg-[#e9c38c] px-4 py-2 text-sm font-black text-[#0b0f19] transition hover:bg-[#f1d5a7]">
                        New request
                    </a>
                </div>

                @forelse($orders as $order)
                    @php
                        $product = $order->product;
                        $canTrack = in_array($order->status, ['payment_review', 'paid'], true) || $order->fulfillment_status;
                    @endphp

                    <article class="mb-4 rounded-xl border border-[#242833] bg-[#0f1115] p-4 transition hover:border-[#e9c38c]/40 sm:p-5">
                        <div class="flex gap-4">
                            <div class="shrink-0">
                                @if($order->product_image)
                                    <img src="{{ str_starts_with($order->product_image, 'http') ? $order->product_image : asset('storage/'.$order->product_image) }}"
                                         alt="{{ $order->product_title }}"
                                         class="h-20 w-20 rounded-lg border border-[#242833] object-cover sm:h-28 sm:w-28"
                                         loading="lazy">
                                @elseif($product && $product->image)
                                    <img src="{{ asset('storage/'.$product->image) }}"
                                         alt="{{ $product->title }}"
                                         class="h-20 w-20 rounded-lg border border-[#242833] object-cover sm:h-28 sm:w-28"
                                         loading="lazy">
                                @else
                                    <div class="flex h-20 w-20 items-center justify-center rounded-lg bg-[#171a21] text-xs text-gray-500 sm:h-28 sm:w-28">
                                        No image
                                    </div>
                                @endif
                            </div>

                            <div class="min-w-0 flex-1">
                                <div class="flex flex-col gap-2 sm:flex-row sm:items-start sm:justify-between">
                                    <div class="min-w-0">
                                        <h4 class="truncate text-base font-bold text-white sm:text-lg">
                                            {{ $order->product_title ?? optional($product)->title ?? 'Product unavailable' }}
                                        </h4>
                                        <p class="mt-1 text-xs text-gray-500">Order #{{ $order->id }}</p>
                                    </div>

                                    <x-status-badge :status="$order->status" />
                                </div>

                                <div class="mt-4 grid gap-2 text-sm text-gray-400 sm:grid-cols-3">
                                    <p>
                                        <span class="block text-xs text-gray-500">Total</span>
                                        <span class="font-bold text-[#e9c38c]">{{ number_format($order->total_dzd ?? 0, 2) }} DZD</span>
                                    </p>

                                    <p>
                                        <span class="block text-xs text-gray-500">Created</span>
                                        <span class="font-semibold text-gray-300">{{ $order->created_at?->format('M d, Y') }}</span>
                                    </p>

                                    <p>
                                        <span class="block text-xs text-gray-500">Fulfillment</span>
                                        <span class="font-semibold text-gray-300">
                                            {{ $order->fulfillment_status ? ucfirst(str_replace('_', ' ', $order->fulfillment_status)) : 'Not started' }}
                                        </span>
                                    </p>
                                </div>

                                @if($order->status === 'pending_payment' && $order->expires_at)
                                    <x-alert type="warning" class="mt-4">
                                        Pay before {{ $order->expires_at->diffForHumans() }} to keep this quote active.
                                    </x-alert>
                                @endif
                            </div>
                        </div>

                        <div class="mt-4 grid gap-2 sm:flex sm:justify-end">
                            @if($order->status === 'pending_payment')
                                <a href="{{ route('orders.payment', $order->id) }}"
                                   class="flex min-h-11 items-center justify-center rounded-lg bg-[#e9c38c] px-5 py-2 text-sm font-black text-[#0b0f19] transition hover:bg-[#f1d5a7]">
                                    Complete payment
                                </a>

                                <form method="POST"
                                      action="{{ route('orders.cancel', $order->id) }}"
                                      onsubmit="return confirm('Cancel this order?')">
                                    @csrf

                                    <button type="submit"
                                            class="flex min-h-11 w-full items-center justify-center rounded-lg border border-red-500/40 px-5 py-2 text-sm font-bold text-red-300 transition hover:bg-red-500/10 sm:w-auto">
                                        Cancel order
                                    </button>
                                </form>
                            @endif

                            @if($canTrack)
                                <a href="{{ route('orders.tracking', $order) }}"
                                   class="flex min-h-11 items-center justify-center rounded-lg border border-[#242833] px-5 py-2 text-sm font-bold text-gray-300 transition hover:border-[#e9c38c] hover:text-[#e9c38c]">
                                    Track order
                                </a>
                            @endif
                        </div>
                    </article>
                @empty
                    <x-empty-state
                        title="No orders yet"
                        message="When you approve a quote and place an order, it will appear here."
                        :action-href="route('welcome')"
                        action-label="Start shopping" />
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
