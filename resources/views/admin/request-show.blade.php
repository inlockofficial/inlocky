@extends('admin.layout', [
    'title' => 'Manage Request #' . $request->id,
    'heading' => 'Manage Request #' . $request->id,
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

        <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
            <a href="{{ route('admin.requests') }}"
               class="inline-flex w-fit rounded-lg border border-[#242833] px-4 py-2 text-sm font-semibold text-gray-300 transition hover:border-[#e9c38c] hover:text-[#e9c38c]">
                Back to Requests
            </a>

            <span class="w-fit rounded-full px-3 py-1 text-xs font-bold
                @if($request->status === 'pending_review')
                    bg-yellow-500/10 text-yellow-300
                @elseif($request->status === 'rejected')
                    bg-red-500/10 text-red-300
                @elseif($request->quote_expires_at && $request->quote_expires_at->isPast())
                    bg-orange-500/10 text-orange-300
                @else
                    bg-blue-500/10 text-blue-300
                @endif">
                {{ ucfirst(str_replace('_', ' ', $request->status)) }}
            </span>
        </div>

        <div class="grid gap-6 xl:grid-cols-3">
            <div class="space-y-6 xl:col-span-1">
                <div class="rounded-lg border border-[#242833] bg-[#111827] p-5">
                    <h2 class="text-lg font-bold text-white">Request Details</h2>

                    <div class="mt-5 space-y-4 text-sm">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-wide text-gray-500">AliExpress Link</p>
                            <a href="{{ $request->ali_link }}" target="_blank" class="mt-1 block break-words text-[#e9c38c] hover:text-[#f1d5a7]">
                                {{ $request->ali_link }}
                            </a>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-xs font-bold uppercase tracking-wide text-gray-500">Color</p>
                                <p class="mt-1 text-gray-200">{{ $request->color ?: 'Not provided' }}</p>
                            </div>

                            <div>
                                <p class="text-xs font-bold uppercase tracking-wide text-gray-500">Size</p>
                                <p class="mt-1 text-gray-200">{{ $request->size ?: 'Not provided' }}</p>
                            </div>

                            <div>
                                <p class="text-xs font-bold uppercase tracking-wide text-gray-500">Gender</p>
                                <p class="mt-1 text-gray-200">{{ $request->gender ?: 'Not provided' }}</p>
                            </div>

                            <div>
                                <p class="text-xs font-bold uppercase tracking-wide text-gray-500">Quantity</p>
                                <p class="mt-1 text-gray-200">{{ $request->quantity ?? 1 }}</p>
                            </div>
                        </div>

                        <div>
                            <p class="text-xs font-bold uppercase tracking-wide text-gray-500">User Note</p>
                            <p class="mt-1 whitespace-pre-line text-gray-200">{{ $request->custom_note ?: 'No note provided.' }}</p>
                        </div>

                        <div>
                            <p class="text-xs font-bold uppercase tracking-wide text-gray-500">Quote Expiration</p>
                            @if($request->quote_expires_at)
                                <p class="mt-1 text-gray-200">{{ $request->quote_expires_at->format('M d, Y H:i') }}</p>
                                <p class="text-xs {{ $request->quote_expires_at->isPast() ? 'text-orange-300' : 'text-gray-500' }}">
                                    {{ $request->quote_expires_at->diffForHumans() }}
                                </p>
                            @else
                                <p class="mt-1 text-gray-500">Not set</p>
                            @endif
                        </div>

                        <div>
                            <p class="text-xs font-bold uppercase tracking-wide text-gray-500">Service Fee</p>
                            @if($request->service_fee_dzd !== null)
                                <p class="mt-1 text-gray-200">{{ number_format($request->service_fee_dzd, 2) }} DZD</p>
                            @else
                                <p class="mt-1 text-gray-500">Not set</p>
                            @endif
                        </div>

                        @if($request->status === 'rejected')
                            <div class="rounded-lg border border-red-500/20 bg-red-500/10 p-4">
                                <p class="text-xs font-bold uppercase tracking-wide text-red-300">Rejection Reason</p>
                                <p class="mt-2 whitespace-pre-line text-sm text-red-100">
                                    {{ $request->rejection_reason ?: 'No reason provided.' }}
                                </p>

                                @if($request->rejected_at)
                                    <p class="mt-2 text-xs text-red-300">
                                        Rejected {{ $request->rejected_at->diffForHumans() }}
                                    </p>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>

                @if($request->screenshot)
                    <div class="rounded-lg border border-[#242833] bg-[#111827] p-5">
                        <h2 class="text-lg font-bold text-white">Reference Screenshot</h2>

                        <img src="{{ asset('storage/' . $request->screenshot) }}"
                             alt="Reference screenshot"
                             class="mt-4 w-full rounded-lg border border-[#242833] object-contain">
                    </div>
                @endif
            </div>

            <div class="space-y-6 xl:col-span-2">
                <div class="rounded-lg border border-[#242833] bg-[#111827] p-5">
                    <h2 class="text-lg font-bold text-white">Pricing Information</h2>

                    <form action="{{ route('admin.request.update', $request->id) }}"
                          method="POST"
                          enctype="multipart/form-data"
                          class="mt-6 space-y-5">
                        @csrf

                        <div>
                            <label for="title" class="block text-sm font-semibold text-gray-300">Title *</label>
                            <input id="title"
                                   type="text"
                                   name="title"
                                   value="{{ old('title', $request->title) }}"
                                   required
                                   class="mt-2 block w-full rounded-lg border-[#242833] bg-[#0b0f19] text-white focus:border-[#e9c38c] focus:ring-[#e9c38c]">
                        </div>

                        <div>
                            <label for="image" class="block text-sm font-semibold text-gray-300">
                                Product Image {{ $request->image ? '' : '*' }}
                            </label>

                            @if($request->image)
                                <div class="mt-3 flex items-center gap-4 rounded-lg border border-[#242833] bg-[#0b0f19] p-3">
                                    <img src="{{ str_starts_with($request->image, 'http') ? $request->image : asset('storage/' . $request->image) }}"
                                         alt="{{ $request->title ?: 'Product image' }}"
                                         class="h-20 w-20 rounded-lg object-cover">
                                    <div>
                                        <p class="text-sm font-semibold text-white">Current image</p>
                                        <p class="text-xs text-gray-500">Upload a new file only if you want to replace it.</p>
                                    </div>
                                </div>
                            @endif

                            <input id="image"
                                   type="file"
                                   name="image"
                                   accept="image/*"
                                   {{ $request->image ? '' : 'required' }}
                                   class="mt-3 block w-full rounded-lg border border-[#242833] bg-[#0b0f19] px-3 py-2 text-sm text-gray-300 file:mr-4 file:rounded-lg file:border-0 file:bg-[#e9c38c] file:px-4 file:py-2 file:font-bold file:text-[#0b0f19]">
                        </div>

                        <div class="grid gap-4 md:grid-cols-2">
                            <div>
                                <label for="price_usd" class="block text-sm font-semibold text-gray-300">Price USD *</label>
                                <input id="price_usd"
                                       type="number"
                                       step="0.01"
                                       min="0"
                                       name="price_usd"
                                       value="{{ old('price_usd', $request->price_usd) }}"
                                       required
                                       class="mt-2 block w-full rounded-lg border-[#242833] bg-[#0b0f19] text-white focus:border-[#e9c38c] focus:ring-[#e9c38c]">
                            </div>

                            <div>
                                <label for="shipping_usd" class="block text-sm font-semibold text-gray-300">Shipping USD</label>
                                <input id="shipping_usd"
                                       type="number"
                                       step="0.01"
                                       min="0"
                                       name="shipping_usd"
                                       value="{{ old('shipping_usd', $request->shipping_usd ?? 0) }}"
                                       class="mt-2 block w-full rounded-lg border-[#242833] bg-[#0b0f19] text-white focus:border-[#e9c38c] focus:ring-[#e9c38c]">
                            </div>
                        </div>

                        <div class="grid gap-4 md:grid-cols-2">
                            <div>
                                <label for="service_fee_dzd" class="block text-sm font-semibold text-gray-300">Service Fee DZD</label>
                                <input id="service_fee_dzd"
                                       type="number"
                                       step="0.01"
                                       min="0"
                                       name="service_fee_dzd"
                                       value="{{ old('service_fee_dzd', $request->service_fee_dzd ?? 0) }}"
                                       class="mt-2 block w-full rounded-lg border-[#242833] bg-[#0b0f19] text-white focus:border-[#e9c38c] focus:ring-[#e9c38c]">
                            </div>

                            <div>
                                <label for="quote_expires_at" class="block text-sm font-semibold text-gray-300">Quote Expires At</label>
                                <input id="quote_expires_at"
                                       type="datetime-local"
                                       name="quote_expires_at"
                                       value="{{ old('quote_expires_at', $request->quote_expires_at ? $request->quote_expires_at->format('Y-m-d\TH:i') : now()->addDay()->format('Y-m-d\TH:i')) }}"
                                       class="mt-2 block w-full rounded-lg border-[#242833] bg-[#0b0f19] text-white focus:border-[#e9c38c] focus:ring-[#e9c38c]">
                            </div>
                        </div>

                        @if($request->final_price_dzd)
                            <div class="rounded-lg border border-[#242833] bg-[#0b0f19] p-4">
                                <p class="text-xs font-bold uppercase tracking-wide text-gray-500">Current Final Price</p>
                                <p class="mt-1 text-2xl font-black text-[#e9c38c]">
                                    {{ number_format($request->final_price_dzd, 2) }} DZD
                                </p>
                            </div>
                        @endif

                        <button type="submit"
                                class="w-full rounded-lg bg-[#e9c38c] px-5 py-3 text-sm font-black text-[#0b0f19] transition hover:bg-[#f1d5a7]">
                            Update Product Quote
                        </button>
                    </form>
                </div>

                <div class="rounded-lg border border-red-500/20 bg-red-500/10 p-5">
                    <h2 class="text-lg font-bold text-red-100">Reject Request</h2>
                    <p class="mt-1 text-sm text-red-200/80">
                        Rejecting this request prevents it from appearing in the pending pricing inbox.
                    </p>

                    <form action="{{ route('admin.request.reject', $request->id) }}"
                          method="POST"
                          class="mt-5 space-y-4"
                          onsubmit="return confirm('Reject this request?')">
                        @csrf

                        <div>
                            <label for="rejection_reason" class="block text-sm font-semibold text-red-100">
                                Rejection Reason Optional
                            </label>
                            <textarea id="rejection_reason"
                                      name="rejection_reason"
                                      rows="4"
                                      class="mt-2 block w-full rounded-lg border-red-500/30 bg-[#0b0f19] text-white focus:border-red-400 focus:ring-red-400"
                                      placeholder="Example: Product is unavailable, link is invalid, or details are incomplete.">{{ old('rejection_reason', $request->rejection_reason) }}</textarea>
                        </div>

                        <button type="submit"
                                class="rounded-lg border border-red-400 px-5 py-3 text-sm font-bold text-red-100 transition hover:bg-red-500/20">
                            Reject Request
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
