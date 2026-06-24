@extends('admin.layout', [
    'title' => 'Request Management',
    'heading' => 'Request Management',
])

@section('content')
    <div class="space-y-6">
        <div class="grid gap-4 md:grid-cols-3">
            <a href="{{ route('admin.requests', ['tab' => 'pending']) }}"
               class="rounded-lg border p-5 transition
               {{ $tab === 'pending' ? 'border-[#e9c38c] bg-[#e9c38c] text-[#0b0f19]' : 'border-[#242833] bg-[#111827] text-white hover:border-[#e9c38c]' }}">
                <p class="text-sm font-bold {{ $tab === 'pending' ? 'text-[#0b0f19]/70' : 'text-gray-400' }}">
                    Pending Review
                </p>
                <p class="mt-3 text-3xl font-black">{{ number_format($counts['pending']) }}</p>
            </a>

            <a href="{{ route('admin.requests', ['tab' => 'expired']) }}"
               class="rounded-lg border p-5 transition
               {{ $tab === 'expired' ? 'border-[#e9c38c] bg-[#e9c38c] text-[#0b0f19]' : 'border-[#242833] bg-[#111827] text-white hover:border-[#e9c38c]' }}">
                <p class="text-sm font-bold {{ $tab === 'expired' ? 'text-[#0b0f19]/70' : 'text-gray-400' }}">
                    Expired Quotes
                </p>
                <p class="mt-3 text-3xl font-black">{{ number_format($counts['expired']) }}</p>
            </a>

            <a href="{{ route('admin.requests', ['tab' => 'rejected']) }}"
               class="rounded-lg border p-5 transition
               {{ $tab === 'rejected' ? 'border-[#e9c38c] bg-[#e9c38c] text-[#0b0f19]' : 'border-[#242833] bg-[#111827] text-white hover:border-[#e9c38c]' }}">
                <p class="text-sm font-bold {{ $tab === 'rejected' ? 'text-[#0b0f19]/70' : 'text-gray-400' }}">
                    Rejected Requests
                </p>
                <p class="mt-3 text-3xl font-black">{{ number_format($counts['rejected']) }}</p>
            </a>
        </div>

        <div class="rounded-lg border border-[#242833] bg-[#111827]">
            <div class="flex flex-col gap-3 border-b border-[#242833] px-5 py-4 md:flex-row md:items-center md:justify-between">
                <div>
                    <h2 class="text-lg font-bold text-white">
                        @if($tab === 'expired')
                            Expired Quotes
                        @elseif($tab === 'rejected')
                            Rejected Requests
                        @else
                            Pending Review Inbox
                        @endif
                    </h2>
                    <p class="mt-1 text-sm text-gray-400">
                        @if($tab === 'expired')
                            Priced products whose quote expiration timestamp has passed.
                        @elseif($tab === 'rejected')
                            Requests rejected by an admin, including rejection reason when provided.
                        @else
                            New product requests waiting for pricing.
                        @endif
                    </p>
                </div>

                <a href="{{ route('admin.dashboard') }}"
                   class="rounded-lg border border-[#242833] px-4 py-2 text-sm font-semibold text-gray-300 transition hover:border-[#e9c38c] hover:text-[#e9c38c]">
                    Back to Dashboard
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-[#242833]">
                    <thead class="bg-[#0f1115]">
                        <tr>
                            <th class="px-5 py-3 text-left text-xs font-bold uppercase tracking-wider text-gray-500">ID</th>
                            <th class="px-5 py-3 text-left text-xs font-bold uppercase tracking-wider text-gray-500">AliExpress Link</th>
                            <th class="px-5 py-3 text-left text-xs font-bold uppercase tracking-wider text-gray-500">Status</th>
                            <th class="px-5 py-3 text-left text-xs font-bold uppercase tracking-wider text-gray-500">Quote Expiration</th>
                            <th class="px-5 py-3 text-left text-xs font-bold uppercase tracking-wider text-gray-500">Service Fee</th>
                            <th class="px-5 py-3 text-left text-xs font-bold uppercase tracking-wider text-gray-500">Reason</th>
                            <th class="px-5 py-3 text-right text-xs font-bold uppercase tracking-wider text-gray-500">Action</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-[#242833]">
                        @forelse($requests as $req)
                            <tr class="hover:bg-[#0f1115]">
                                <td class="whitespace-nowrap px-5 py-4 text-sm font-bold text-white">
                                    #{{ $req->id }}
                                </td>

                                <td class="max-w-xs px-5 py-4 text-sm">
                                    <a href="{{ $req->ali_link }}"
                                       target="_blank"
                                       class="block truncate text-[#e9c38c] hover:text-[#f1d5a7]">
                                        {{ $req->ali_link }}
                                    </a>
                                </td>

                                <td class="whitespace-nowrap px-5 py-4 text-sm">
                                    <span class="rounded-full px-3 py-1 text-xs font-bold
                                        @if($req->status === 'pending_review')
                                            bg-yellow-500/10 text-yellow-300
                                        @elseif($req->status === 'rejected')
                                            bg-red-500/10 text-red-300
                                        @elseif($req->quote_expires_at && $req->quote_expires_at->isPast())
                                            bg-orange-500/10 text-orange-300
                                        @else
                                            bg-blue-500/10 text-blue-300
                                        @endif">
                                        {{ ucfirst(str_replace('_', ' ', $req->status)) }}
                                    </span>
                                </td>

                                <td class="whitespace-nowrap px-5 py-4 text-sm text-gray-300">
                                    @if($req->quote_expires_at)
                                        <div>{{ $req->quote_expires_at->format('M d, Y H:i') }}</div>
                                        <div class="text-xs {{ $req->quote_expires_at->isPast() ? 'text-orange-300' : 'text-gray-500' }}">
                                            {{ $req->quote_expires_at->diffForHumans() }}
                                        </div>
                                    @else
                                        <span class="text-gray-500">Not set</span>
                                    @endif
                                </td>

                                <td class="whitespace-nowrap px-5 py-4 text-sm text-gray-300">
                                    @if($req->service_fee_dzd !== null)
                                        {{ number_format($req->service_fee_dzd, 2) }} DZD
                                    @else
                                        <span class="text-gray-500">Not set</span>
                                    @endif
                                </td>

                                <td class="max-w-xs px-5 py-4 text-sm text-gray-300">
                                    @if($req->rejection_reason)
                                        <span class="line-clamp-2">{{ $req->rejection_reason }}</span>
                                    @else
                                        <span class="text-gray-500">None</span>
                                    @endif
                                </td>

                                <td class="whitespace-nowrap px-5 py-4 text-right text-sm">
                                    <a href="{{ route('admin.request.show', $req->id) }}"
                                       class="inline-flex rounded-lg bg-[#e9c38c] px-4 py-2 font-bold text-[#0b0f19] transition hover:bg-[#f1d5a7]">
                                        Manage
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-5 py-12 text-center text-sm text-gray-400">
                                    No requests found for this section.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
