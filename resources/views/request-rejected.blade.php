<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>INLOCK — Request Rejected</title>

    @vite(['resources/css/app.css','resources/js/app.js'])
</head>

<body class="bg-[#0b0f19] text-white min-h-screen flex items-center justify-center p-4 md:p-8">

    <div class="absolute inset-0 opacity-20 blur-3xl pointer-events-none
        bg-[radial-gradient(circle_at_center,#e9c38c_0%,transparent_60%)]">
    </div>

    <div class="
        relative
        bg-[#171a21]
        border border-[#242833]
        rounded-2xl
        shadow-xl
        p-8 md:p-10
        max-w-2xl
        w-full
        text-center
    ">
        <h1 class="text-3xl font-bold mb-3 tracking-wide">
            <span class="text-[#e9c38c]">INLOCK</span>
        </h1>

        <div class="mx-auto mb-6 flex h-16 w-16 items-center justify-center rounded-full border border-red-500/30 bg-red-500/10">
            <span class="text-3xl text-red-400">!</span>
        </div>

        <h2 class="text-2xl font-bold text-white">
            Your request could not be approved
        </h2>

        <p class="mt-3 text-gray-400 leading-relaxed">
            Our team reviewed your product request and could not continue with this quote.
        </p>

        <div class="mt-8 rounded-xl border border-[#242833] bg-[#0f1115] p-5 text-left">
            <p class="text-xs font-bold uppercase tracking-[0.2em] text-gray-500">
                Request
            </p>

            <p class="mt-2 break-words text-sm text-gray-300">
                <a href="{{ $request->ali_link }}" target="_blank" class="text-[#e9c38c] hover:text-[#f1d5a7]">
                    {{ $request->ali_link }}
                </a>
            </p>

            <div class="mt-5 border-t border-[#242833] pt-5">
                <p class="text-xs font-bold uppercase tracking-[0.2em] text-gray-500">
                    Reason
                </p>

                <p class="mt-2 whitespace-pre-line text-gray-200">
                    {{ $request->rejection_reason ?: 'No specific reason was provided.' }}
                </p>
            </div>

            @if($request->rejected_at)
                <p class="mt-5 text-xs text-gray-500">
                    Rejected {{ $request->rejected_at->diffForHumans() }}
                </p>
            @endif
        </div>

        <div class="mt-8 flex flex-col gap-3 sm:flex-row">
            <a href="{{ route('welcome') }}"
               class="flex-1 rounded-xl bg-[#e9c38c] px-5 py-3 text-sm font-black text-[#0b0f19] transition hover:bg-[#f1d5a7]">
                Submit Another Request
            </a>

            <a href="{{ route('dashboard') }}"
               class="flex-1 rounded-xl border border-[#242833] px-5 py-3 text-sm font-bold text-gray-300 transition hover:border-[#e9c38c] hover:text-[#e9c38c]">
                Go to Dashboard
            </a>
        </div>
    </div>

</body>
</html>
