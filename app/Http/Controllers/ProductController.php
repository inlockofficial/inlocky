<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class ProductController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'ali_link' => 'required|url',
            'screenshot' => 'nullable|image|max:2048'
        ]);

        $url = $request->ali_link;
        $cleanUrl = strtok($url, '?');

        $screenshotPath = null;

        if ($request->hasFile('screenshot')) {
            $screenshotPath = $request->file('screenshot')
                ->store('screenshots', 'public');
        }

        $requestData = [
            'ali_link' => $cleanUrl,
            'color' => $request->color,
            'size' => $request->size,
            'quantity' => $request->quantity,
            'gender' => $request->gender,
            'custom_note' => $request->custom_note,
            'screenshot' => $screenshotPath,
        ];

        // Guests can fill out the form, but we need an account to attach
        // the request to. Preserve everything they entered (the screenshot
        // is already safely on disk) and resume the submission right after
        // they register or log in — see HandlesPendingProductRequest.
        if (!auth()->check()) {
            session(['pending_product_request' => $requestData]);

            return redirect()
                ->route('register')
                ->with('status', "We've saved your request — create an account to get your DZD price.");
        }

        $product = Product::create($requestData + [
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('request.waiting', $product->id);
    }

    /**
     * "My Requests" — every request the authenticated user has made,
     * grouped by where it stands in the lifecycle.
     */
    public function myRequests()
    {
        $requests = Product::where('user_id', auth()->id())
            ->with('order')
            ->latest()
            ->get();

        $grouped = [
            'converted' => $requests->filter(
                fn (Product $product) => $product->order !== null
            )->values(),

            'priced' => $requests->filter(function (Product $product) {
                return $product->order === null
                    && $product->status === 'priced'
                    && !($product->quote_expires_at && $product->quote_expires_at->isPast());
            })->values(),

            'pending' => $requests->filter(
                fn (Product $product) => $product->order === null && $product->status === 'pending_review'
            )->values(),

            'expired' => $requests->filter(function (Product $product) {
                return $product->order === null
                    && $product->status === 'priced'
                    && $product->quote_expires_at
                    && $product->quote_expires_at->isPast();
            })->values(),

            'rejected' => $requests->filter(
                fn (Product $product) => $product->order === null && $product->status === 'rejected'
            )->values(),
        ];

        return view('requests.index', [
            'grouped' => $grouped,
        ]);
    }

    public function adminIndex(Request $request)
    {
        $tab = $request->query('tab', 'pending');

        $counts = [
            'pending' => Product::where('status', 'pending_review')->count(),
            'expired' => Product::where('status', 'priced')
                ->whereNotNull('quote_expires_at')
                ->where('quote_expires_at', '<', now())
                ->count(),
            'rejected' => Product::where('status', 'rejected')->count(),
        ];

        $requests = match ($tab) {
            'expired' => Product::where('status', 'priced')
                ->whereNotNull('quote_expires_at')
                ->where('quote_expires_at', '<', now())
                ->latest()
                ->get(),

            'rejected' => Product::where('status', 'rejected')
                ->latest()
                ->get(),

            default => Product::where('status', 'pending_review')
                ->latest()
                ->get(),
        };

        return view('admin.requests', compact('requests', 'counts', 'tab'));
    }

    public function adminShow($id)
    {
        $request = Product::findOrFail($id);

        return view('admin.request-show', compact('request'));
    }

    public function adminUpdate(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'image' => ($product->image ? 'nullable' : 'required') . '|file|mimes:jpg,jpeg,png,webp,avif|max:2048',
            'price_usd' => 'required|numeric|min:0',
            'shipping_usd' => 'nullable|numeric|min:0',
            'service_fee_dzd' => 'nullable|numeric|min:0',
            'quote_expires_at' => 'nullable|date',
        ]);

        $rate = config('app.usd_to_dzd');

        $priceUsd = (float) $request->price_usd;
        $shippingUsd = (float) ($request->shipping_usd ?? 0);
        $serviceFeeDzd = (float) ($request->service_fee_dzd ?? 0);

        $rawPrice = ($priceUsd + $shippingUsd) * $rate;
        $rawPrice += $serviceFeeDzd;

        $finalDzd = ceil($rawPrice / 100) * 100;

        $imagePath = $product->image;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')
                ->store('products', 'public');
        }

        $product->update([
            'title' => $request->title,
            'image' => $imagePath,
            'price_usd' => $priceUsd,
            'shipping_usd' => $shippingUsd,
            'service_fee_dzd' => $serviceFeeDzd,
            'final_price_dzd' => $finalDzd,
            'rate_used' => $rate,
            'quote_expires_at' => $request->quote_expires_at,
            'rejection_reason' => null,
            'rejected_at' => null,
            'status' => 'priced',
        ]);

        return redirect()
            ->route('admin.requests')
            ->with('success', 'Product updated!');
    }

    public function adminReject(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $validated = $request->validate([
            'rejection_reason' => 'nullable|string|max:2000',
        ]);

        $product->update([
            'status' => 'rejected',
            'rejection_reason' => $validated['rejection_reason'] ?? null,
            'rejected_at' => now(),
            'quote_expires_at' => null,
        ]);

        return redirect()
            ->route('admin.requests', ['tab' => 'rejected'])
            ->with('success', 'Request rejected.');
    }
}
