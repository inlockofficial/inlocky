<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $product = Product::findOrFail($request->product_id);

        $order = Order::create([
            'user_id'         => Auth::id(),
            'product_id'      => $product->id,
            'product_title'   => $product->title,
            'product_image'   => $product->image,
            'selected_size'   => $product->size,
            'selected_color'  => $product->color,
            'custom_note'     => $product->custom_note,
            'quantity'        => $product->quantity,
            'price_usd'       => $product->price_usd,
            'rate_used'       => $product->rate_used,
            'total_dzd'       => $product->final_price_dzd,
            'status'          => 'pending_payment',
            'expires_at'      => now()->addHours(24),
        ]);

        return redirect()->route('orders.payment', $order->id);
    }

    public function index()
    {
        $orders = Order::where('user_id', Auth::id())
            ->latest()
            ->with(['product', 'payments'])
            ->get();

        return view('dashboard', compact('orders'));
    }

    public function payment(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        if (
            $order->status === 'cancelled' ||
            ($order->expires_at && now()->greaterThan($order->expires_at))
        ) {
            abort(403, 'This order expired due to currency changes.');
        }

        $product = $order->product;

        return view('orders.payment', compact('order', 'product'));
    }

    public function tracking(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        $order->load(['product', 'payments']);

        return view('orders.tracking', [
            'order' => $order,
            'timelineSteps' => $this->customerTimeline($order),
        ]);
    }

    public function checkout(Request $request, Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'full_name'   => 'required|string|max:255',
            'email'       => 'required|email',
            'phone'       => 'required|string|max:30',
            'address'     => 'required|string|max:1000',
            'city'        => 'required|string|max:255',
            'postal_code' => 'nullable|string|max:20',
            'notes'       => 'nullable|string|max:1000',
        ]);

        $order->update($validated);

        return redirect()->route('chargilypay.redirect', [
            'product_id' => $order->product_id,
            'order_id'   => $order->id,
        ]);
    }

    public function cancel(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        if ($order->status !== 'pending_payment') {
            return back()->with('error', 'Order cannot be cancelled.');
        }

        $order->update([
            'status' => 'cancelled',
        ]);

        return redirect()
            ->route('dashboard')
            ->with('success', 'Order cancelled successfully.');
    }

    private function customerTimeline(Order $order): array
    {
        $paidPayment = $order->payments->firstWhere('status', 'paid');
        $paidAt = $paidPayment?->updated_at ?? null;

        return [
            [
                'key' => 'payment_received',
                'label' => 'Payment received',
                'description' => 'Your payment was received and your order is waiting for processing.',
                'complete' => (bool) $paidPayment || in_array($order->status, ['payment_review', 'paid', 'ordered', 'shipped', 'completed'], true),
                'date' => $paidAt,
            ],
            [
                'key' => 'ordered_on_ali',
                'label' => 'Ordered from supplier',
                'description' => 'Your item has been ordered from the supplier.',
                'complete' => $this->fulfillmentReached($order, 'ordered_on_ali'),
                'date' => $this->timelineDate($order, 'ordered_on_ali'),
            ],
            [
                'key' => 'in_transit_to_dz',
                'label' => 'In transit',
                'description' => 'Your item is on its way to Algeria.',
                'complete' => $this->fulfillmentReached($order, 'in_transit_to_dz'),
                'date' => $this->timelineDate($order, 'in_transit_to_dz'),
            ],
            [
                'key' => 'arrived_at_local_office',
                'label' => 'Arrived in Algeria',
                'description' => 'Your item has arrived locally and is being prepared.',
                'complete' => $this->fulfillmentReached($order, 'arrived_at_local_office'),
                'date' => $this->timelineDate($order, 'arrived_at_local_office'),
            ],
            [
                'key' => 'ready_for_delivery',
                'label' => 'Ready for delivery',
                'description' => 'Your item is ready for final delivery coordination.',
                'complete' => $this->fulfillmentReached($order, 'arrived_at_local_office'),
                'date' => $this->timelineDate($order, 'arrived_at_local_office'),
            ],
            [
                'key' => 'delivered',
                'label' => 'Delivered',
                'description' => 'Your order has been delivered.',
                'complete' => $this->fulfillmentReached($order, 'delivered'),
                'date' => $this->timelineDate($order, 'delivered'),
            ],
        ];
    }

    private function fulfillmentReached(Order $order, string $status): bool
    {
        $sequence = [
            'ordered_on_ali',
            'in_transit_to_dz',
            'arrived_at_local_office',
            'delivered',
        ];

        if (!$order->fulfillment_status) {
            return false;
        }

        $currentIndex = array_search($order->fulfillment_status, $sequence, true);
        $targetIndex = array_search($status, $sequence, true);

        return $currentIndex !== false && $targetIndex !== false && $currentIndex >= $targetIndex;
    }

    private function timelineDate(Order $order, string $status): ?Carbon
    {
        $timeline = collect($order->fulfillment_timeline ?? []);
        $event = $timeline->firstWhere('status', $status);

        if (!$event || empty($event['at'])) {
            return null;
        }

        return Carbon::parse($event['at']);
    }
}
