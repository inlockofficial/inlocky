<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    public function process(Request $request, Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'full_name'   => 'required|string|max:255',
            'email'       => 'required|email',
            'phone'       => 'required|string|max:30',
            'address'     => 'required|string|max:1000',
            'wilaya'      => 'nullable|string|max:255',
            'commune'     => 'nullable|string|max:255',
            'city'        => 'required|string|max:255',
            'postal_code' => 'nullable|string|max:20',
            'notes'       => 'nullable|string|max:1000',
        ]);

        $order->update($validated);

        return view('payments.redirect_to_chargily', [
            'order_id' => $order->id
        ]);
    }
}
