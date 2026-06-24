<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChargilyPayment;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class DashboardController extends Controller
{
    public function index()
    {
        $now = now();

        $pendingRequestsCount = Product::where('status', 'pending_review')->count();

        $paidOrdersCount = Order::whereIn('status', ['payment_review', 'paid'])
            ->whereHas('payments', function (Builder $query) {
                $query->where('status', 'paid');
            })
            ->count();

        $paidOrders = $this->paidOrdersQuery()
            ->with('product')
            ->get();

        $revenueDzd = (float) $paidOrders->sum('total_dzd');
        $costUsd = $this->estimateCostUsd($paidOrders);
        $costDzd = $this->estimateCostDzd($paidOrders);
        $estimatedProfitDzd = $revenueDzd - $costDzd;

        $pricedQuotesCount = Product::whereIn('status', ['priced', 'completed'])->count();

        $convertedQuotesCount = Product::whereIn('status', ['priced', 'completed'])
            ->whereHas('orders', function (Builder $query) {
                $query->whereHas('payments', function (Builder $paymentQuery) {
                    $paymentQuery->where('status', 'paid');
                });
            })
            ->count();

        $quoteConversionRate = $pricedQuotesCount > 0
            ? round(($convertedQuotesCount / $pricedQuotesCount) * 100, 1)
            : 0;

        $periodStats = [
            'daily' => $this->buildPeriodStats(
                'Today',
                $now->copy()->startOfDay(),
                $now->copy()->endOfDay()
            ),
            'weekly' => $this->buildPeriodStats(
                'This Week',
                $now->copy()->startOfWeek(),
                $now->copy()->endOfWeek()
            ),
            'monthly' => $this->buildPeriodStats(
                'This Month',
                $now->copy()->startOfMonth(),
                $now->copy()->endOfMonth()
            ),
        ];

        $recentPendingRequests = Product::where('status', 'pending_review')
            ->latest()
            ->take(5)
            ->get();

        $recentPaidOrders = Order::whereIn('status', ['payment_review', 'paid'])
            ->whereHas('payments', function (Builder $query) {
                $query->where('status', 'paid');
            })
            ->with(['user', 'product'])
            ->latest()
            ->take(5)
            ->get();

        $successfulPaymentsCount = ChargilyPayment::where('status', 'paid')->count();
        $failedPaymentsCount = ChargilyPayment::where('status', 'failed')->count();
        $pendingPaymentsCount = ChargilyPayment::where('status', 'pending')->count();

        return view('admin.dashboard', [
            'pendingRequestsCount' => $pendingRequestsCount,
            'paidOrdersCount' => $paidOrdersCount,
            'revenueDzd' => $revenueDzd,
            'costUsd' => $costUsd,
            'costDzd' => $costDzd,
            'estimatedProfitDzd' => $estimatedProfitDzd,
            'quoteConversionRate' => $quoteConversionRate,
            'pricedQuotesCount' => $pricedQuotesCount,
            'convertedQuotesCount' => $convertedQuotesCount,
            'periodStats' => $periodStats,
            'recentPendingRequests' => $recentPendingRequests,
            'recentPaidOrders' => $recentPaidOrders,
            'successfulPaymentsCount' => $successfulPaymentsCount,
            'failedPaymentsCount' => $failedPaymentsCount,
            'pendingPaymentsCount' => $pendingPaymentsCount,
        ]);
    }

    private function paidOrdersQuery(): Builder
    {
        return Order::query()
            ->whereHas('payments', function (Builder $query) {
                $query->where('status', 'paid');
            });
    }

    private function buildPeriodStats(string $label, Carbon $start, Carbon $end): array
    {
        $paidOrders = $this->paidOrdersQuery()
            ->whereHas('payments', function (Builder $query) use ($start, $end) {
                $query->where('status', 'paid')
                    ->whereBetween('updated_at', [$start, $end]);
            })
            ->with('product')
            ->get();

        $revenueDzd = (float) $paidOrders->sum('total_dzd');
        $costUsd = $this->estimateCostUsd($paidOrders);
        $costDzd = $this->estimateCostDzd($paidOrders);

        return [
            'label' => $label,
            'orders_count' => $paidOrders->count(),
            'revenue_dzd' => $revenueDzd,
            'cost_usd' => $costUsd,
            'cost_dzd' => $costDzd,
            'estimated_profit_dzd' => $revenueDzd - $costDzd,
            'pending_requests_count' => Product::where('status', 'pending_review')
                ->whereBetween('created_at', [$start, $end])
                ->count(),
            'successful_payments_count' => ChargilyPayment::where('status', 'paid')
                ->whereBetween('updated_at', [$start, $end])
                ->count(),
        ];
    }

    private function estimateCostUsd(Collection $orders): float
    {
        return (float) $orders->sum(function (Order $order) {
            return (float) $order->price_usd + (float) optional($order->product)->shipping_usd;
        });
    }

    private function estimateCostDzd(Collection $orders): float
    {
        return (float) $orders->sum(function (Order $order) {
            $exchangeRate = (float) ($order->rate_used ?: config('app.usd_to_dzd', 265));
            $costUsd = (float) $order->price_usd + (float) optional($order->product)->shipping_usd;

            return $costUsd * $exchangeRate;
        });
    }
}
