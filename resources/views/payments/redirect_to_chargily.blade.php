<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redirecting to Payment</title>

    @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#0b0f19] px-4 py-6 text-white sm:flex sm:items-center sm:justify-center">
    <main class="mx-auto w-full max-w-md rounded-2xl border border-[#242833] bg-[#171a21] p-6 text-center shadow-xl sm:p-8">
        <p class="text-2xl font-black text-[#e9c38c]">INLOCK</p>

        <h1 class="mt-4 text-xl font-bold text-white">
            Redirecting to secure payment
        </h1>

        <p class="mt-3 text-sm leading-relaxed text-gray-400">
            We are opening the Chargily payment page. If nothing happens, use the button below.
        </p>

        <div class="mx-auto mt-8 h-14 w-14 animate-spin rounded-full border-2 border-[#e9c38c] border-t-transparent"></div>

        <form id="chargilyForm" action="{{ route('chargilypay.redirect') }}" method="POST" class="mt-8">
            @csrf
            <input type="hidden" name="order_id" value="{{ $order_id }}">

            <button type="submit"
                    class="flex min-h-12 w-full items-center justify-center rounded-xl bg-[#e9c38c] px-5 py-3 text-base font-black text-[#0b0f19] transition hover:bg-[#f1d5a7]">
                Continue to payment
            </button>
        </form>
    </main>

    <script>
        window.setTimeout(() => {
            document.getElementById('chargilyForm').submit();
        }, 900);
    </script>
</body>
</html>
