@extends('admin.layout', [
    'title' => 'Order Fulfillment',
    'heading' => 'Order Fulfillment',
])

@section('content')
    <div class="rounded-lg border border-[#242833] bg-[#111827]">
        <div class="border-b border-[#242833] px-5 py-4">
            <h2 class="text-lg font-bold text-white">Paid Order Queue</h2>
            <p class="mt-1 text-sm text-gray-400">
                Orders with payment status paid or payment review, ready for admin fulfillment.
            </p>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-[#242833]">
                <thead class="bg-[#0f1115]">
                    <tr>
                        <th class="px-5 py-3 text-left text-xs font-bold uppercase tracking-wider text-gray-500">Order ID</th>
                        <th class="px-5 py-3 text-left text-xs font-bold uppercase tracking-wider text-gray-500">Customer</th>
                        <th class="px-5 py-3 text-left text-xs font-bold uppercase tracking-wider text-gray-500">Product</th>
                        <th class="px-5 py-3 text-left text-xs font-bold uppercase tracking-wider text-gray-500">Order Date</th>
                        <th class="px-5 py-3 text-left text-xs font-bold uppercase tracking-wider text-gray-500">Fulfillment</th>
                        <th class="px-5 py-3 text-right text-xs font-bold uppercase tracking-wider text-gray-500">Action</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-[#242833]">
                    @forelse($orders as $order)
                        <tr class="hover:bg-[#0f1115]">
                            <td class="whitespace-nowrap px-5 py-4 text-sm font-bold text-white">
                                #{{ $order->id }}
                            </td>

                            <td class="whitespace-nowrap px-5 py-4 text-sm text-gray-300">
                                {{ $order->full_name ?: optional($order->user)->name ?: 'Unknown customer' }}
                            </td>

                            <td class="max-w-sm px-5 py-4 text-sm text-gray-300">
                                <span class="block truncate">
                                    {{ $order->product_title ?: optional($order->product)->title ?: 'Product unavailable' }}
                                </span>
                            </td>

                            <td class="whitespace-nowrap px-5 py-4 text-sm text-gray-400">
                                {{ $order->created_at?->format('M d, Y H:i') }}
                            </td>

                            <td class="whitespace-nowrap px-5 py-4 text-sm">
                                @if($order->fulfillment_status)
                                    <span class="rounded-full bg-blue-500/10 px-3 py-1 text-xs font-bold text-blue-300">
                                        {{ $statusLabels[$order->fulfillment_status] ?? ucfirst(str_replace('_', ' ', $order->fulfillment_status)) }}
                                    </span>
                                @else
                                    <span class="rounded-full bg-yellow-500/10 px-3 py-1 text-xs font-bold text-yellow-300">
                                        Not started
                                    </span>
                                @endif
                            </td>

                            <td class="whitespace-nowrap px-5 py-4 text-right text-sm">
                                <a href="{{ route('admin.orders.show', $order) }}"
                                   class="inline-flex rounded-lg bg-[#e9c38c] px-4 py-2 font-bold text-[#0b0f19] transition hover:bg-[#f1d5a7]">
                                    View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-12 text-center text-sm text-gray-400">
                                No paid orders are waiting for fulfillment.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($orders->hasPages())
            <div class="border-t border-[#242833] px-5 py-4">
                {{ $orders->links() }}
            </div>
        @endif
    </div>
@endsection
