<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    // Place Order (COD)
public function placeOrder(Request $request)
{
    $user = Auth::user();
    $cart = Cart::with('items.product')->where('user_id', $user->id)->first();

    if (!$cart || $cart->items->isEmpty()) {
        return redirect()->back()->with('error', 'Your cart is empty.');
    }

    // Create the order
    $order = Order::create([
        'user_id' => $user->id,
        'address_id' => $request->input('address_id'),
        'total_amount' => $cart->items->sum(fn($item) => $item->quantity * $item->product->price),
        'payment_status' => 'pending',
        'order_status' => 'pending',
    ]);

    // Add order items
    foreach ($cart->items as $item) {
        $order->items()->create([
            'product_id' => $item->product_id,
            'quantity' => $item->quantity,
            'price' => $item->product->price,
        ]);
    }

    // Clear cart
    $cart->items()->delete();

    // Redirect to the success page with order ID
    return redirect()->route('orders.success', $order->id);
}





public function orderSuccess($orderId)
{
    $order = Order::with('items.product', 'address')->findOrFail($orderId);

    return view('orders.success', compact('order'));
}

    public function trackOrder($orderId)
    {
        $order = Order::with('items.product')->where('id', $orderId)->where('user_id', Auth::id())->firstOrFail();
        return view('orders.track', compact('order'));
    }
}
