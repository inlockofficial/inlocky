<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-white">
            Secure Checkout
        </h2>
    </x-slot>

    <div class="mx-auto max-w-6xl px-4 py-6 sm:px-6 lg:px-8">
        @if ($errors->any())
            <x-alert type="error" title="Please check your details" class="mb-5">
                Some fields need your attention before continuing to payment.
            </x-alert>
        @endif

        <div class="grid gap-6 lg:grid-cols-12">
            <aside class="lg:col-span-4">
                <div class="rounded-xl border border-[#242833] bg-[#111827] p-5 lg:sticky lg:top-6">
                    <p class="text-xs font-bold uppercase tracking-wide text-gray-500">Order summary</p>

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
                                {{ $order->product_title ?: $product->title }}
                            </p>
                            <p class="mt-1 text-xs text-gray-500">Order #{{ $order->id }}</p>
                        </div>
                    </div>

                    <div class="mt-5 space-y-3 border-t border-[#242833] pt-5 text-sm">
                        <div class="flex justify-between gap-4">
                            <span class="text-gray-400">Quantity</span>
                            <span class="font-bold text-white">{{ $order->quantity ?? 1 }}</span>
                        </div>

                        <div class="flex justify-between gap-4">
                            <span class="text-gray-400">Total</span>
                            <span class="font-black text-[#e9c38c]">{{ number_format($order->total_dzd, 2) }} DZD</span>
                        </div>
                    </div>

                    @if($order->expires_at)
                        <x-alert type="warning" class="mt-5">
                            Pay before {{ $order->expires_at->format('M d, Y H:i') }}. Unpaid orders may expire due to exchange rate changes.
                        </x-alert>
                    @endif
                </div>
            </aside>

            <section class="lg:col-span-8">
                <div class="rounded-xl border border-[#242833] bg-[#111827] p-5 shadow-xl sm:p-7">
                    <div>
                        <h1 class="text-2xl font-black text-white">Delivery details</h1>
                        <p class="mt-2 text-sm leading-relaxed text-gray-400">
                            Enter the contact and address details we should use for this order.
                        </p>
                    </div>

                    <form method="POST" action="{{ route('checkout.process', $order->id) }}" class="mt-6 space-y-5">
                        @csrf

                        <input type="hidden" name="order_id" value="{{ $order->id }}">

                        <div class="grid gap-5 sm:grid-cols-2">
                            <div>
                                <x-input-label for="full_name" value="Full name" />
                                <x-text-input id="full_name" type="text" name="full_name" class="mt-2 min-h-11 w-full"
                                              :value="old('full_name', $order->full_name ?: auth()->user()->name)" required autocomplete="name" />
                                <x-input-error class="mt-2" :messages="$errors->get('full_name')" />
                            </div>

                            <div>
                                <x-input-label for="email" value="Email" />
                                <x-text-input id="email" type="email" name="email" class="mt-2 min-h-11 w-full"
                                              :value="old('email', $order->email ?: auth()->user()->email)" required autocomplete="email" />
                                <x-input-error class="mt-2" :messages="$errors->get('email')" />
                            </div>

                            <div>
                                <x-input-label for="phone" value="Phone number" />
                                <x-text-input id="phone" type="tel" name="phone" class="mt-2 min-h-11 w-full"
                                              :value="old('phone', $order->phone)" required autocomplete="tel" />
                                <x-input-error class="mt-2" :messages="$errors->get('phone')" />
                            </div>

                            <div>
                                <x-input-label for="city" value="City" />
                                <x-text-input id="city" type="text" name="city" class="mt-2 min-h-11 w-full"
                                              :value="old('city', $order->city)" required />
                                <x-input-error class="mt-2" :messages="$errors->get('city')" />
                            </div>

                            <div>
                                <x-input-label for="wilaya" value="Wilaya optional" />
                                <x-text-input id="wilaya" type="text" name="wilaya" class="mt-2 min-h-11 w-full"
                                              :value="old('wilaya', $order->wilaya)" />
                                <x-input-error class="mt-2" :messages="$errors->get('wilaya')" />
                            </div>

                            <div>
                                <x-input-label for="commune" value="Commune optional" />
                                <x-text-input id="commune" type="text" name="commune" class="mt-2 min-h-11 w-full"
                                              :value="old('commune', $order->commune)" />
                                <x-input-error class="mt-2" :messages="$errors->get('commune')" />
                            </div>

                            <div>
                                <x-input-label for="postal_code" value="Postal code optional" />
                                <x-text-input id="postal_code" type="text" name="postal_code" class="mt-2 min-h-11 w-full"
                                              :value="old('postal_code', $order->postal_code)" />
                                <x-input-error class="mt-2" :messages="$errors->get('postal_code')" />
                            </div>
                        </div>

                        <div>
                            <x-input-label for="address" value="Full address" />
                            <textarea id="address"
                                      name="address"
                                      rows="4"
                                      required
                                      class="mt-2 block w-full rounded-md border-[#242833] bg-[#0b0f19] text-white focus:border-[#e9c38c] focus:ring-[#e9c38c]">{{ old('address', $order->address) }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('address')" />
                        </div>

                        <div>
                            <x-input-label for="notes" value="Delivery notes optional" />
                            <textarea id="notes"
                                      name="notes"
                                      rows="3"
                                      class="mt-2 block w-full rounded-md border-[#242833] bg-[#0b0f19] text-white focus:border-[#e9c38c] focus:ring-[#e9c38c]">{{ old('notes', $order->notes) }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('notes')" />
                        </div>

                        <button type="submit"
                                class="flex min-h-12 w-full items-center justify-center rounded-xl bg-[#e9c38c] px-5 py-3 text-base font-black text-[#0b0f19] transition hover:bg-[#f1d5a7]">
                            Proceed to payment
                        </button>
                    </form>
                </div>
            </section>
        </div>
    </div>
</x-app-layout>
