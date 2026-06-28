<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Admin Dashboard' }} - INLOCK</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#0b0f19] text-white">
    <div class="min-h-screen lg:flex">
        <aside class="border-b border-[#242833] bg-[#111827] lg:min-h-screen lg:w-72 lg:border-b-0 lg:border-r">
            <div class="px-6 py-6">
                <a href="{{ route('admin.dashboard') }}" class="block">
                    <div class="text-xl font-black tracking-wide text-[#e9c38c]">
                        INLOCK
                    </div>
                    <div class="mt-1 text-xs font-semibold uppercase tracking-[0.2em] text-gray-500">
                        Admin
                    </div>
                </a>
            </div>

            <nav class="space-y-1 px-4 pb-6">
                <a href="{{ route('admin.dashboard') }}"
                   class="flex items-center justify-between rounded-lg px-4 py-3 text-sm font-semibold transition
                   {{ request()->routeIs('admin.dashboard') ? 'bg-[#e9c38c] text-[#0b0f19]' : 'text-gray-300 hover:bg-[#171a21] hover:text-white' }}">
                    <span>Overview</span>
                </a>

                <a href="{{ route('admin.requests') }}"
                   class="flex items-center justify-between rounded-lg px-4 py-3 text-sm font-semibold transition
                   {{ request()->routeIs('admin.requests') || request()->routeIs('admin.request.*') ? 'bg-[#e9c38c] text-[#0b0f19]' : 'text-gray-300 hover:bg-[#171a21] hover:text-white' }}">
                    <span>Requests</span>
                </a>

                <a href="{{ route('admin.orders.index') }}"
                   class="flex items-center justify-between rounded-lg px-4 py-3 text-sm font-semibold transition
                   {{ request()->routeIs('admin.orders.*') ? 'bg-[#e9c38c] text-[#0b0f19]' : 'text-gray-300 hover:bg-[#171a21] hover:text-white' }}">
                    <span>Orders</span>
                </a>

                <a href="{{ route('dashboard') }}"
                   class="flex items-center justify-between rounded-lg px-4 py-3 text-sm font-semibold text-gray-300 transition hover:bg-[#171a21] hover:text-white">
                    <span>User Dashboard</span>
                </a>
            </nav>
        </aside>

        <main class="min-w-0 flex-1">
            <header class="border-b border-[#242833] bg-[#0f1115]/80 px-4 py-5 backdrop-blur md:px-8">
                <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-[0.2em] text-[#e9c38c]">
                            Admin Panel
                        </p>
                        <h1 class="mt-1 text-2xl font-bold text-white">
                            {{ $heading ?? 'Overview Dashboard' }}
                        </h1>
                    </div>

                    <div class="flex items-center gap-3 text-sm text-gray-400">
                        <span>{{ auth()->user()->name }}</span>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <button type="submit"
                                    class="rounded-lg border border-[#242833] px-4 py-2 font-semibold text-gray-300 transition hover:border-[#e9c38c] hover:text-[#e9c38c]">
                                Log Out
                            </button>
                        </form>
                    </div>
                </div>
            </header>

            <section class="px-4 py-6 md:px-8 md:py-8">
                @if(session('success'))
                    <div class="mb-6 rounded-lg border border-green-500/30 bg-green-500/10 px-4 py-3 text-sm text-green-300">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-6 rounded-lg border border-red-500/30 bg-red-500/10 px-4 py-3 text-sm text-red-300">
                        {{ session('error') }}
                    </div>
                @endif

                @yield('content')
            </section>
        </main>
    </div>
</body>
</html>
