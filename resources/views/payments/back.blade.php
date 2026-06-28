@php
    $isSuccess = $status === 'paid';
    $isFailure = in_array($status, ['failed', 'canceled', 'cancelled'], true);
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment Status</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#0b0f19] px-4 py-6 text-white sm:flex sm:items-center sm:justify-center">
    <main class="mx-auto w-full max-w-md rounded-2xl border border-[#242833] bg-[#171a21] p-6 text-center shadow-xl sm:p-8">
        <p class="text-2xl font-black text-[#e9c38c]">INLOCK</p>

        <div class="mx-auto mt-6 flex h-16 w-16 items-center justify-center rounded-full border
            @if($isSuccess)
                border-green-500/30 bg-green-500/10 text-green-300
            @elseif($isFailure)
                border-red-500/30 bg-red-500/10 text-red-300
            @else
                border-yellow-500/30 bg-yellow-500/10 text-yellow-300
            @endif">
            <span class="text-2xl font-black">
                @if($isSuccess)
                    OK
                @elseif($isFailure)
                    !
                @else
                    ...
                @endif
            </span>
        </div>

        <h1 class="mt-5 text-2xl font-black text-white">
            @if($isSuccess)
                Payment received
            @elseif($isFailure)
                Payment was not completed
            @else
                Payment status pending
            @endif
        </h1>

        <p class="mt-3 text-sm leading-relaxed text-gray-400">
            @if($isSuccess)
                Your payment was received. Your order will move into processing after confirmation.
            @elseif($isFailure)
                The payment was canceled or failed. You can return to your dashboard and try again if the order is still available.
            @else
                We are still waiting for final payment confirmation. If you already paid, refresh your dashboard in a few moments.
            @endif
        </p>

        <div class="mt-6 rounded-lg border border-[#242833] bg-[#0f1115] p-4 text-left text-sm">
            <div class="flex justify-between gap-4">
                <span class="text-gray-500">Checkout</span>
                <span class="font-semibold text-gray-200">{{ $checkout_id ?: 'Unknown' }}</span>
            </div>

            <div class="mt-3 flex justify-between gap-4">
                <span class="text-gray-500">Status</span>
                <x-status-badge :status="$isFailure ? 'failed' : $status" />
            </div>

            @if($amount)
                <div class="mt-3 flex justify-between gap-4">
                    <span class="text-gray-500">Amount</span>
                    <span class="font-semibold text-[#e9c38c]">{{ number_format($amount, 2) }} DZD</span>
                </div>
            @endif
        </div>

        <div class="mt-6 grid gap-3">
            <a href="{{ route('dashboard') }}"
               class="flex min-h-12 items-center justify-center rounded-xl bg-[#e9c38c] px-5 py-3 text-base font-black text-[#0b0f19] transition hover:bg-[#f1d5a7]">
                Go to dashboard
            </a>

            <a href="{{ route('welcome') }}"
               class="flex min-h-11 items-center justify-center rounded-xl border border-[#242833] px-5 py-3 text-sm font-bold text-gray-300 transition hover:border-[#e9c38c] hover:text-[#e9c38c]">
                Continue shopping
            </a>
        </div>
    </main>
</body>
</html>
