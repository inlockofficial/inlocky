@extends('admin.layout', [
    'title' => 'Order #' . $order->id,
    'heading' => 'Order #' . $order->id,
])

@section('content')
    <div class="space-y-6">
        @if ($errors->any())
            <div class="rounded-lg border border-red-500/30 bg-red-500/10 px-4 py-3 text-sm text-red-300">
                <ul class="list-inside list-disc space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <a href="{{ route('admin.orders.index') }}"
           class="inline-flex rounded-lg border border-[#242833] px-4 py-2 text-sm font-semibold text-gray-300 transition hover:border-[#e9c38c] hover:text-[#e9c38c]">
            Back to Orders
        </a>

        <div class="grid gap-6 xl:grid-cols-3">
            <div class="space-y-6 xl:col-span-2">
                <div class="rounded-lg border border-[#242833] bg-[#111827] p-5">
                    <h2 class="text-lg font-bold text-white">Purchase Details</h2>

                    <div class="mt-5 grid gap-4 md:grid-cols-2">
                        <div class="md:col-span-2">
                            <p class="text-xs font-bold uppercase tracking-wide text-gray-500">Original AliExpress Link</p>
                            @if(optional($order->product)->ali_link)
                                <a href="{{ $order->product->ali_link }}" target="_blank" class="mt-1 block break-words text-[#e9c38c] hover:text-[#f1d5a7]">
                                    {{ $order->product->ali_link }}
                                </a>
                            @else
                                <p class="mt-1 text-gray-500">Not available</p>
                            @endif
                        </div>

                        <div class="md:col-span-2">
                            <p class="text-xs font-bold uppercase tracking-wide text-gray-500">Product Title</p>
                            <p class="mt-1 text-gray-200">
                                {{ $order->product_title ?: optional($order->product)->title ?: 'Product unavailable' }}
                            </p>
                        </div>

                        <div>
                            <p class="text-xs font-bold uppercase tracking-wide text-gray-500">Selected Size</p>
                            <p class="mt-1 text-gray-200">{{ $order->selected_size ?: 'Not provided' }}</p>
                        </div>

                        <div>
                            <p class="text-xs font-bold uppercase tracking-wide text-gray-500">Selected Color</p>
                            <p class="mt-1 text-gray-200">{{ $order->selected_color ?: 'Not provided' }}</p>
                        </div>

                        <div>
                            <p class="text-xs font-bold uppercase tracking-wide text-gray-500">Quantity</p>
                            <p class="mt-1 text-gray-200">{{ $order->quantity ?? 1 }}</p>
                        </div>

                        <div>
                            <p class="text-xs font-bold uppercase tracking-wide text-gray-500">Order Date</p>
                            <p class="mt-1 text-gray-200">{{ $order->created_at?->format('M d, Y H:i') }}</p>
                        </div>

                        <div class="md:col-span-2">
                            <p class="text-xs font-bold uppercase tracking-wide text-gray-500">User Custom Notes</p>
                            <p class="mt-1 whitespace-pre-line text-gray-200">
                                {{ $order->custom_note ?: 'No custom note provided.' }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="rounded-lg border border-[#242833] bg-[#111827] p-5">
                    <h2 class="text-lg font-bold text-white">Shipping Information</h2>

                    <div class="mt-5 grid gap-4 md:grid-cols-2">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-wide text-gray-500">Full Name</p>
                            <p class="mt-1 text-gray-200">{{ $order->full_name ?: optional($order->user)->name ?: 'Not provided' }}</p>
                        </div>

                        <div>
                            <p class="text-xs font-bold uppercase tracking-wide text-gray-500">Phone Number</p>
                            <p class="mt-1 text-gray-200">{{ $order->phone ?: 'Not provided' }}</p>
                        </div>

                        <div class="md:col-span-2">
                            <p class="text-xs font-bold uppercase tracking-wide text-gray-500">Address</p>
                            <p class="mt-1 whitespace-pre-line text-gray-200">{{ $order->address ?: 'Not provided' }}</p>
                        </div>

                        <div>
                            <p class="text-xs font-bold uppercase tracking-wide text-gray-500">Wilaya</p>
                            <p class="mt-1 text-gray-200">{{ $order->wilaya ?: $order->city ?: 'Not provided' }}</p>
                        </div>

                        <div>
                            <p class="text-xs font-bold uppercase tracking-wide text-gray-500">Commune</p>
                            <p class="mt-1 text-gray-200">{{ $order->commune ?: 'Not provided' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <div class="rounded-lg border border-[#242833] bg-[#111827] p-5">
                    <h2 class="text-lg font-bold text-white">Fulfillment Workflow</h2>

                    <div class="mt-4 rounded-lg border border-[#242833] bg-[#0b0f19] p-4">
                        <p class="text-xs font-bold uppercase tracking-wide text-gray-500">Current Status</p>

                        @if($order->fulfillment_status)
                            <p class="mt-2 text-lg font-bold text-[#e9c38c]">
                                {{ $statusLabels[$order->fulfillment_status] ?? ucfirst(str_replace('_', ' ', $order->fulfillment_status)) }}
                            </p>
                        @else
                            <p class="mt-2 text-lg font-bold text-yellow-300">Not started</p>
                        @endif

                        @if($order->fulfillment_status_updated_at)
                            <p class="mt-1 text-xs text-gray-500">
                                Updated {{ $order->fulfillment_status_updated_at->diffForHumans() }}
                            </p>
                        @endif
                    </div>

                    <form method="POST"
                          action="{{ route('admin.orders.fulfillment.update', $order) }}"
                          class="mt-5 space-y-4">
                        @csrf
                        @method('PATCH')

                        <div>
                            <label for="supplier_tracking_number" class="block text-sm font-semibold text-gray-300">
                                Supplier Tracking Number
                            </label>
                            <input id="supplier_tracking_number"
                                   type="text"
                                   name="supplier_tracking_number"
                                   value="{{ old('supplier_tracking_number', $order->supplier_tracking_number) }}"
                                   class="mt-2 block w-full rounded-lg border-[#242833] bg-[#0b0f19] text-white focus:border-[#e9c38c] focus:ring-[#e9c38c]"
                                   placeholder="AliExpress tracking number">
                            <p class="mt-2 text-xs text-gray-500">
                                Save this when marking the order as Ordered.
                            </p>
                        </div>

                        <div class="grid gap-3">
                            <button type="submit"
                                    name="fulfillment_status"
                                    value="ordered_on_ali"
                                    class="rounded-lg bg-[#e9c38c] px-4 py-3 text-sm font-black text-[#0b0f19] transition hover:bg-[#f1d5a7]">
                                Ordered
                            </button>

                            <button type="submit"
                                    name="fulfillment_status"
                                    value="in_transit_to_dz"
                                    class="rounded-lg border border-[#242833] px-4 py-3 text-sm font-bold text-gray-200 transition hover:border-[#e9c38c] hover:text-[#e9c38c]">
                                In transit
                            </button>

                            <button type="submit"
                                    name="fulfillment_status"
                                    value="arrived_at_local_office"
                                    class="rounded-lg border border-[#242833] px-4 py-3 text-sm font-bold text-gray-200 transition hover:border-[#e9c38c] hover:text-[#e9c38c]">
                                Arrived in Algeria
                            </button>

                            <button type="submit"
                                    name="fulfillment_status"
                                    value="delivered"
                                    class="rounded-lg border border-green-500/40 px-4 py-3 text-sm font-bold text-green-300 transition hover:bg-green-500/10">
                                Delivered
                            </button>
                        </div>
                    </form>
                </div>

                <div class="rounded-lg border border-[#242833] bg-[#111827] p-5">
                    <h2 class="text-lg font-bold text-white">Customer Timeline Data</h2>
                    <p class="mt-1 text-sm text-gray-400">
                        Prepared for future customer notifications.
                    </p>

                    <div class="mt-5 space-y-3">
                        @forelse(array_reverse($timeline) as $event)
                            <div class="rounded-lg border border-[#242833] bg-[#0b0f19] p-4">
                                <p class="text-sm font-bold text-white">
                                    {{ $event['label'] ?? ucfirst(str_replace('_', ' ', $event['status'] ?? 'Unknown')) }}
                                </p>

                                @if(!empty($event['at']))
                                    <p class="mt-1 text-xs text-gray-500">
                                        {{ \Illuminate\Support\Carbon::parse($event['at'])->format('M d, Y H:i') }}
                                    </p>
                                @endif

                                @if(!empty($event['supplier_tracking_number']))
                                    <p class="mt-2 text-xs text-gray-400">
                                        Tracking: <span class="font-semibold text-gray-200">{{ $event['supplier_tracking_number'] }}</span>
                                    </p>
                                @endif
                            </div>
                        @empty
                            <div class="rounded-lg border border-dashed border-[#242833] bg-[#0b0f19] p-4 text-sm text-gray-500">
                                No fulfillment events recorded yet.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
