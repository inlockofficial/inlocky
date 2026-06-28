<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\OrderFulfillmentController;
use App\Http\Controllers\ChargilyPayController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ExtractorController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/mock/products', function () {
    return response()->json([
        'products' => [
            [
                'id' => 1,
                'title' => 'Wireless Headphones',
                'price_usd' => 25.5,
                'image' => '/images/demo1.jpg'
            ],
            [
                'id' => 2,
                'title' => 'Gaming Mouse',
                'price_usd' => 12.9,
                'image' => '/images/demo2.jpg'
            ]
        ]
    ]);
});

require __DIR__.'/auth.php';

Route::post('/orders', [OrderController::class, 'store'])
    ->middleware('auth')
    ->name('orders.store');

Route::get('/orders/{order}/payment', [OrderController::class, 'payment'])
    ->middleware('auth')
    ->name('orders.payment');

Route::get('/orders/{order}/tracking', [OrderController::class, 'tracking'])
    ->middleware('auth')
    ->name('orders.tracking');

Route::post('chargilypay/redirect', [ChargilyPayController::class, 'redirect'])->name('chargilypay.redirect');
Route::get('chargilypay/back', [ChargilyPayController::class, 'back'])->name('chargilypay.back');
Route::post('chargilypay/webhook', [ChargilyPayController::class, 'webhook'])->name('chargilypay.webhook_endpoint');

Route::post('/fetch-product', [ExtractorController::class, 'fetch'])->name('fetch.product');

Route::post('/request', [ProductController::class, 'store'])
    ->middleware('auth')
    ->name('request.store');

Route::get('/request/{id}/waiting', function ($id) {
    $request = \App\Models\Product::findOrFail($id);

    if ($request->user_id !== auth()->id()) {
        abort(403);
    }

    return view('waiting', compact('request'));
})->middleware('auth')->name('request.waiting');

Route::middleware(['auth', 'is_admin'])->group(function () {
    Route::get('/admin', [DashboardController::class, 'index'])->name('admin.dashboard');

    Route::get('/admin/requests', [ProductController::class, 'adminIndex'])->name('admin.requests');
    Route::get('/admin/request/{id}', [ProductController::class, 'adminShow'])->name('admin.request.show');
    Route::post('/admin/request/{id}/update', [ProductController::class, 'adminUpdate'])->name('admin.request.update');
    Route::post('/admin/request/{id}/reject', [ProductController::class, 'adminReject'])->name('admin.request.reject');

    Route::get('/admin/orders', [OrderFulfillmentController::class, 'index'])->name('admin.orders.index');
    Route::get('/admin/orders/{order}', [OrderFulfillmentController::class, 'show'])->name('admin.orders.show');
    Route::patch('/admin/orders/{order}/fulfillment', [OrderFulfillmentController::class, 'update'])->name('admin.orders.fulfillment.update');
});

Route::get('/request/{id}/status', function ($id) {
    $request = \App\Models\Product::findOrFail($id);

    if ($request->user_id !== auth()->id()) {
        abort(403);
    }

    $status = $request->status;

    if (
        $request->status === 'priced' &&
        $request->quote_expires_at &&
        $request->quote_expires_at->isPast()
    ) {
        $status = 'expired';
    }

    return response()->json([
        'status' => $status,
        'redirect_url' => match ($status) {
            'priced', 'expired' => route('request.view', $request->id),
            'rejected' => route('request.rejected', $request->id),
            default => null,
        },
    ]);
})->middleware('auth')->name('request.status');

Route::get('/request/{id}/view', function ($id) {
    $product = \App\Models\Product::findOrFail($id);

    if ($product->user_id !== auth()->id()) {
        abort(403);
    }

    if ($product->status !== 'priced') {
        return redirect()->route('request.waiting', $id);
    }

    return view('request-view', compact('product'));
})->middleware('auth')->name('request.view');

Route::get('/request/{id}/rejected', function ($id) {
    $request = \App\Models\Product::findOrFail($id);

    if ($request->user_id !== auth()->id()) {
        abort(403);
    }

    if ($request->status !== 'rejected') {
        return redirect()->route('request.waiting', $id);
    }

    return view('request-rejected', compact('request'));
})->middleware('auth')->name('request.rejected');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [OrderController::class, 'index'])
        ->name('dashboard');
});

Route::get('/orders/create', function () {
    return redirect()->route('welcome');
})->name('orders.create');

Route::post('/checkout/{order}', [CheckoutController::class, 'process'])
    ->middleware('auth')
    ->name('checkout.process');

Route::post('/orders/{order}/cancel', [OrderController::class, 'cancel'])
    ->name('orders.cancel')
    ->middleware('auth');

Route::get('/debug-vendor-file', function () {
    $path = base_path('vendor/cloudinary-labs/cloudinary-laravel/src/CloudinaryServiceProvider.php');

    if (!file_exists($path)) {
        return response()->json(['error' => 'File not found at ' . $path]);
    }

    $lines = file($path);
    $output = [];

    for ($i = 54; $i <= 74; $i++) {
        if (isset($lines[$i])) {
            $output[$i + 1] = trim($lines[$i]);
        }
    }

    return response()->json($output);
});
