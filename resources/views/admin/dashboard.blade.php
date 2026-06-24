@extends('admin.layout', [
    'title' => 'Overview Dashboard',
    'heading' => 'Overview Dashboard',
])

@section('content')
    <div class="space-y-8">
        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-5">
            <div class="rounded-lg border border-[#242833] bg-[#111827] p-5">
                <p class="text-sm font-semibold text-gray-400">Pending Requests</p>
                <div class="mt-3 flex items-end justify-between gap-4">
                    <p class="text-3xl font-black text-white">{{ number_format($pendingRequestsCount) }}</p>
                    <a href="{{ route('admin.requests') }}" class="text-sm font-semibold text-[#e9c38c] hover:text-[#f1d5a7]">
                        View
                    </a>
                </div>
            </div>

            <div class="rounded-lg border border-[#242833] bg-[#111827] p-5">
                <p class="text-sm font-semibold text-gray-400">Paid Orders</p>
                <div class="mt-3 flex items-end justify-between gap-4">
                    <p class="text-3xl font-black text-white">{{ number_format($paidOrdersCount) }}</p>
                    <span class="text-xs font-bold uppercase tracking-wide text-gray-500">Waiting fulfillment</span>
                </div>
            </div>

            <div class="rounded-lg border border-[#242833] bg-[#111827] p-5">
                <p class="text-sm font-semibold text-gray-400">Revenue</p>
                <p class="mt-3 text-3xl font-black text-[#e9c38c]">
                    {{ number_format($revenueDzd, 2) }}
                </p>
                <p class="mt-1 text-xs font-bold uppercase tracking-wide text-gray-500">DZD</p>
            </div>

            <div class="rounded-lg border border-[#242833] bg-[#111827] p-5">
                <p class="text-sm font-semibold text-gray-400">Estimated Cost</p>
                <p class="mt-3 text-3xl font-black text-white">
                    {{ number_format($costUsd, 2) }}
                </p>
                <p class="mt-1 text-xs font-bold uppercase tracking-wide text-gray-500">
                    USD / {{ number_format($costDzd, 2) }} DZD
                </p>
            </div>

            <div class="rounded-lg border border-[#242833] bg-[#111827] p-5">
                <p class="text-sm font-semibold text-gray-400">Estimated Profit</p>
                <p class="mt-3 text-3xl font-black {{ $estimatedProfitDzd >= 0 ? 'text-green-400' : 'text-red-400' }}">
                    {{ number_format($estimatedProfitDzd, 2) }}
                </p>
                <p class="mt-1 text-xs font-bold uppercase tracking-wide text-gray-500">DZD</p>
            </div>
        </div>

        <div class="grid gap-4 lg:grid-cols-4">
            <div class="rounded-lg border border-[#242833] bg-[#111827] p-5 lg:col-span-2">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <p class="text-sm font-semibold text-gray-400">Quote Conversion Rate</p>
                        <p class="mt-3 text-4xl font-black text-white">{{ number_format($quoteConversionRate, 1) }}%</p>
                    </div>

                    <div class="text-right text-sm text-gray-400">
                        <p>
                            <span class="font-semibold text-white">{{ number_format($convertedQuotesCount) }}</span>
                            converted
                        </p>
                        <p>
                            <span class="font-semibold text-white">{{ number_format($pricedQuotesCount) }}</span>
                            priced quotes
                        </p>
                    </div>
                </div>

                <div class="mt-5 h-2 overflow-hidden rounded-full bg-[#0b0f19]">
                    <div class="h-full rounded-full bg-[#e9c38c]" style="width: {{ min($quoteConversionRate, 100) }}%"></div>
                </div>
            </div>

            <div class="rounded-lg border border-[#242833] bg-[#111827] p-5">
                <p class="text-sm font-semibold text-gray-400">Successful Payments</p>
                <p class="mt-3 text-3xl font-black text-green-400">{{ number_format($successfulPaymentsCount) }}</p>
            </div>

            <div class="rounded-lg border border-[#242833] bg-[#111827] p-5">
                <p class="text-sm font-semibold text-gray-400">Payment Attention</p>
                <div class="mt-3 space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-400">Pending</span>
                        <span class="font-bold text-yellow-300">{{ number_format($pendingPaymentsCount) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400">Failed</span>
                        <span class="font-bold text-red-400">{{ number_format($failedPaymentsCount) }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div>
            <div class="mb-4 flex items-center justify-between">
                <h2 class="text-lg font-bold text-white">Daily, Weekly, Monthly Statistics</h2>
            </div>

            <div class="grid gap-4 xl:grid-cols-3">
                @foreach($periodStats as $stats)
                    <div class="rounded-lg border border-[#242833] bg-[#111827] p-5">
                        <div class="flex items-center justify-between">
                            <h3 class="font-bold text-white">{{ $stats['label'] }}</h3>
                            <span class="rounded-full bg-[#0b0f19] px-3 py-1 text-xs font-bold text-gray-400">
                                {{ number_format($stats['orders_count']) }} paid orders
                            </span>
                        </div>

                        <div class="mt-5 space-y-3 text-sm">
                            <div class="flex justify-between gap-4">
                                <span class="text-gray-400">Revenue</span>
                                <span class="font-bold text-[#e9c38c]">{{ number_format($stats['revenue_dzd'], 2) }} DZD</span>
                            </div>

                            <div class="flex justify-between gap-4">
                                <span class="text-gray-400">Cost</span>
                                <span class="font-bold text-white">{{ number_format($stats['cost_usd'], 2) }} USD</span>
                            </div>

                            <div class="flex justify-between gap-4">
                                <span class="text-gray-400">Estimated profit</span>
                                <span class="font-bold {{ $stats['estimated_profit_dzd'] >= 0 ? 'text-green-400' : 'text-red-400' }}">
                                    {{ number_format($stats['estimated_profit_dzd'], 2) }} DZD
                                </span>
                            </div>

                            <div class="flex justify-between gap-4">
                                <span class="text-gray-400">New requests</span>
                                <span class="font-bold text-white">{{ number_format($stats['pending_requests_count']) }}</span>
                            </div>

                            <div class="flex justify-between gap-4">
                                <span class="text-gray-400">Successful payments</span>
                                <span class="font-bold text-white">{{ number_format($stats['successful_payments_count']) }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="grid gap-6 xl:grid-cols-2">
            <div class="rounded-lg border border-[#242833] bg-[#111827]">
                <div class="border-b border-[#242833] px-5 py-4">
                    <h2 class="text-lg font-bold text-white">Recent Pending Requests</h2>
                </div>

                <div class="divide-y divide-[#242833]">
                    @forelse($recentPendingRequests as $request)
                        <div class="flex items-center justify-between gap-4 px-5 py-4">
                            <div class="min-w-0">
                                <p class="truncate text-sm font-bold text-white">
                                    Request #{{ $request->id }}
                                </p>
                                <a href="{{ $request->ali_link }}" target="_blank" class="mt-1 block truncate text-xs text-gray-400 hover:text-[#e9c38c]">
                                    {{ $request->ali_link }}
                                </a>
                            </div>

                            <a href="{{ route('admin.request.show', $request->id) }}"
                               class="shrink-0 rounded-lg bg-[#e9c38c] px-4 py-2 text-sm font-bold text-[#0b0f19] transition hover:bg-[#f1d5a7]">
                                Price
                            </a>
                        </div>
                    @empty
                        <div class="px-5 py-10 text-center text-sm text-gray-400">
                            No pending requests.
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="rounded-lg border border-[#242833] bg-[#111827]">
                <div class="border-b border-[#242833] px-5 py-4">
                    <h2 class="text-lg font-bold text-white">Recent Paid Orders</h2>
                </div>

                <div class="divide-y divide-[#242833]">
                    @forelse($recentPaidOrders as $order)
                        <div class="px-5 py-4">
                            <div class="flex items-start justify-between gap-4">
                                <div class="min-w-0">
                                    <p class="truncate text-sm font-bold text-white">
                                        Order #{{ $order->id }}
                                    </p>
                                    <p class="mt-1 truncate text-xs text-gray-400">
                                        {{ $order->product_title ?? optional($order->product)->title ?? 'Product unavailable' }}
                                    </p>
                                </div>

                                <span class="shrink-0 rounded-full bg-green-500/10 px-3 py-1 text-xs font-bold text-green-400">
                                    {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                </span>
                            </div>

                            <div class="mt-3 grid gap-2 text-xs text-gray-400 sm:grid-cols-3">
                                <div>
                                    <span class="block text-gray-500">Customer</span>
                                    <span class="font-semibold text-gray-300">{{ optional($order->user)->name ?? 'Unknown' }}</span>
                                </div>

                                <div>
                                    <span class="block text-gray-500">Total</span>
                                    <span class="font-semibold text-[#e9c38c]">{{ number_format($order->total_dzd, 2) }} DZD</span>
                                </div>

                                <div>
                                    <span class="block text-gray-500">Created</span>
                                    <span class="font-semibold text-gray-300">{{ $order->created_at?->format('M d, Y') }}</span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="px-5 py-10 text-center text-sm text-gray-400">
                            No paid orders waiting for review.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection
