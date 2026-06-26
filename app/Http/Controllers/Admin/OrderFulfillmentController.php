<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderFulfillmentController extends Controller
{
    private const FULFILLMENT_STATUSES = [
        'ordered_on_ali' => 'Ordered',
        'in_transit_to_dz' => 'In transit',
        'arrived_at_local_office' => 'Arrived in Algeria',
        'delivered' => 'Delivered',
    ];

    public function index(): View
    {
        $orders = Order::query()
            ->whereIn('status', ['payment_review', 'paid'])
            ->with(['user', 'product'])
            ->latest()
            ->paginate(20);

        return view('admin.orders.index', [
            'orders' => $orders,
            'statusLabels' => self::FULFILLMENT_STATUSES,
        ]);
    }

    public function show(Order $order): View
    {
        $order->load(['user', 'product', 'payments']);

        return view('admin.orders.show', [
            'order' => $order,
            'statusLabels' => self::FULFILLMENT_STATUSES,
            'timeline' => $order->fulfillment_timeline ?? [],
        ]);
    }

    public function update(Request $request, Order $order): RedirectResponse
    {
        $validated = $request->validate([
            'fulfillment_status' => ['required', 'string', 'in:' . implode(',', array_keys(self::FULFILLMENT_STATUSES))],
            'supplier_tracking_number' => ['nullable', 'string', 'max:255'],
        ]);

        $newStatus = $validated['fulfillment_status'];
        $trackingNumber = $validated['supplier_tracking_number'] ?? $order->supplier_tracking_number;

        $timeline = $order->fulfillment_timeline ?? [];

        $timeline[] = [
            'status' => $newStatus,
            'label' => self::FULFILLMENT_STATUSES[$newStatus],
            'at' => now()->toISOString(),
            'admin_id' => auth()->id(),
            'admin_name' => auth()->user()?->name,
            'supplier_tracking_number' => $trackingNumber,
        ];

        $order->update([
            'fulfillment_status' => $newStatus,
            'supplier_tracking_number' => $trackingNumber,
            'fulfillment_status_updated_at' => now(),
            'fulfillment_timeline' => $timeline,
        ]);

        return redirect()
            ->route('admin.orders.show', $order)
            ->with('success', 'Fulfillment status updated.');
    }
}
