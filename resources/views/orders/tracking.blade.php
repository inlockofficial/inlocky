<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-white">
            Track Order #{{ $order->id }}
        </h2>
    </x-slot>

    <div class="mx-auto max-w-5xl px-4 py-6 sm:px-6 lg:px-8">
        <div class="grid gap-6 lg:grid-cols-12">
            <aside class="lg:col-span-4">
                <div class="rounded-xl border border-[#242833] bg-[#111827] p-5 lg:sticky lg:top-6">
                    <p class="text-xs font-bold uppercase tracking-wide text-gray-500">Order</p>

                    <div class="mt-4 flex gap-4">
                        @if($order->product_image)
                            <img src="{{ str_starts_with($order->product_image, 'http') ? $order->product_image : asset('storage/'.$order->product_image) }}"
                                 alt="{{ $order->product_title }}"
                                 class="h-20 w-20 rounded-lg border border-[#242833] object-cover">
                        @else
                            <div class="flex h-20 w-20 items-center justify-center rounded-lg border border-[#242833] bg-[#0b0f19] text-xs text-gray-500">
                                No image
                            </div>
                        @endif

                        <div class="min-w-0 flex-1">
                            <p class="text-sm font-bold leading-snug text-white">
                                {{ $order->product_title ?: optional($order->product)->title ?: 'Product unavailable' }}
                            </p>
                            <p class="mt-1 text-xs text-gray-500">#{{ $order->id }}</p>
                        </div>
                    </div>

                    <div class="mt-5 space-y-3 border-t border-[#242833] pt-5 text-sm">
                        <div class="flex justify-between gap-4">
                            <span class="text-gray-400">Payment</span>
                            <x-status-badge :status="$order->status" />
                        </div>

                        <div class="flex justify-between gap-4">
                            <span class="text-gray-400">Fulfillment</span>
                            <span class="font-semibold text-gray-200">
                                {{ $order->fulfillment_status ? ucfirst(str_replace('_', ' ', $order->fulfillment_status)) : 'Not started' }}
                            </span>
                        </div>

                        @if($order->supplier_tracking_number)
                            <div>
                                <span class="block text-gray-400">Supplier tracking</span>
                                <span class="mt-1 block break-words font-semibold text-[#e9c38c]">
                                    {{ $order->supplier_tracking_number }}
                                </span>
                            </div>
                        @endif
                    </div>
                </div>
            </aside>

            <section class="lg:col-span-8">
                <div class="rounded-xl border border-[#242833] bg-[#111827] p-5 shadow-xl sm:p-7">
                    <h1 class="text-2xl font-black text-white">Order timeline</h1>
                    <p class="mt-2 text-sm leading-relaxed text-gray-400">
                        Follow your order from payment confirmation through delivery.
                    </p>

                    <div class="mt-8 space-y-4">
                        @foreach($timelineSteps as $step)
                            <div class="flex gap-4 rounded-xl border border-[#242833] bg-[#0b0f19] p-4">
                                <div class="pt-1">
                                    <div class="flex h-8 w-8 items-center justify-center rounded-full border
                                        {{ $step['complete'] ? 'border-[#e9c38c] bg-[#e9c38c] text-[#0b0f19]' : 'border-[#242833] bg-[#111827] text-gray-500' }}">
                                        <span class="text-xs font-black">{{ $step['complete'] ? 'OK' : '' }}</span>
                                    </div>
                                </div>

                                <div class="min-w-0 flex-1">
                                    <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
                                        <h2 class="font-bold {{ $step['complete'] ? 'text-white' : 'text-gray-400' }}">
                                            {{ $step['label'] }}
                                        </h2>

                                        @if($step['date'])
                                            <span class="text-xs text-gray-500">
                                                {{ $step['date']->format('M d, Y H:i') }}
                                            </span>
                                        @endif
                                    </div>

                                    <p class="mt-1 text-sm leading-relaxed text-gray-500">
                                        {{ $step['description'] }}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-8 grid gap-3 sm:flex sm:justify-end">
                        <a href="{{ route('dashboard') }}"
                           class="flex min-h-11 items-center justify-center rounded-lg border border-[#242833] px-5 py-2 text-sm font-bold text-gray-300 transition hover:border-[#e9c38c] hover:text-[#e9c38c]">
                            Back to dashboard
                        </a>
                    </div>
                </div>
            </section>
        </div>
    </div>
</x-app-layout>
