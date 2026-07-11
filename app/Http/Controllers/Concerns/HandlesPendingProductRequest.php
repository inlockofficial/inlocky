<?php

namespace App\Http\Controllers\Concerns;

use App\Models\Product;
use Illuminate\Http\RedirectResponse;

/**
 * Shared by RegisteredUserController and AuthenticatedSessionController.
 *
 * When a guest submits the product request form on the landing page or
 * dashboard, ProductController::store() cannot create the record yet
 * (there's no user to attach it to), so it stashes the validated input in
 * the session and sends the visitor to register/login.
 *
 * Immediately after successful authentication, this trait checks for that
 * stashed submission, creates the Product for the now-authenticated user,
 * and sends them straight to the waiting page — exactly as if they had
 * been logged in when they first submitted the form.
 */
trait HandlesPendingProductRequest
{
    protected function redirectAfterAuthWithPendingRequest(string $fallbackRouteName = 'dashboard'): RedirectResponse
    {
        $pending = session()->pull('pending_product_request');

        if (!$pending) {
            return redirect()->route($fallbackRouteName);
        }

        $product = Product::create($pending + [
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('request.waiting', $product->id);
    }
}
