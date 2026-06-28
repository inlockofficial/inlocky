@props([
    'status',
    'label' => null,
])

@php
    $classes = [
        'pending_review' => 'bg-yellow-500/10 text-yellow-300 border-yellow-500/30',
        'pending_payment' => 'bg-yellow-500/10 text-yellow-300 border-yellow-500/30',
        'priced' => 'bg-green-500/10 text-green-300 border-green-500/30',
        'expired' => 'bg-orange-500/10 text-orange-300 border-orange-500/30',
        'rejected' => 'bg-red-500/10 text-red-300 border-red-500/30',
        'payment_review' => 'bg-blue-500/10 text-blue-300 border-blue-500/30',
        'paid' => 'bg-green-500/10 text-green-300 border-green-500/30',
        'failed' => 'bg-red-500/10 text-red-300 border-red-500/30',
        'cancelled' => 'bg-red-500/10 text-red-300 border-red-500/30',
        'ordered_on_ali' => 'bg-blue-500/10 text-blue-300 border-blue-500/30',
        'in_transit_to_dz' => 'bg-indigo-500/10 text-indigo-300 border-indigo-500/30',
        'arrived_at_local_office' => 'bg-purple-500/10 text-purple-300 border-purple-500/30',
        'delivered' => 'bg-green-500/10 text-green-300 border-green-500/30',
    ];

    $badgeClass = $classes[$status] ?? 'bg-gray-500/10 text-gray-300 border-gray-500/30';
    $displayLabel = $label ?? ucfirst(str_replace('_', ' ', $status));
@endphp

<span {{ $attributes->merge(['class' => 'inline-flex items-center rounded-full border px-3 py-1 text-xs font-bold ' . $badgeClass]) }}>
    {{ $displayLabel }}
</span>
