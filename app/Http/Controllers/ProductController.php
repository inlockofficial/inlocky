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

        $product = Product::create([
            'ali_link' => $cleanUrl,
            'user_id' => auth()->id(),
            'color' => $request->color,
            'size' => $request->size,
            'quantity' => $request->quantity,
            'gender' => $request->gender,
            'custom_note' => $request->custom_note,
            'screenshot' => $screenshotPath,
        ]);

        return redirect()->route('request.waiting', $product->id);
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
