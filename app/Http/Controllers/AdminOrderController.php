<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderStatusUpdated;

class AdminOrderController extends Controller
{
    // Show all orders
    public function index()
    {
        $orders = Order::all();
        return view('admin.orders.index', compact('orders'));
    }

    // Show edit form for an order
    public function edit(Order $order)
    {
        return view('admin.orders.edit', compact('order'));
    }

    // Handle order update
public function update(Request $request, Order $order)
{
    $request->validate([
        'order_status' => 'required|string',
        'payment_status' => 'required|string',
    ]);

    $order->update([
        'order_status' => $request->order_status,
        'payment_status' => $request->payment_status,
    ]);

    // Send email
    if ($order->user && $order->user->email) {
        Mail::to($order->user->email)->send(new OrderStatusUpdated($order));
    }

    return redirect()->route('orders.index')->with('success', 'Order updated successfully and email sent!');
}

}
