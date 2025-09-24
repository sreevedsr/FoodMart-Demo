@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h2>Order Placed Successfully!</h2>
    <p>Your order has been placed with <b>Cash on Delivery</b> payment.</p>

    <hr>

    <h4>Order Tracking</h4>
    <p><b>Order ID:</b> {{ $order->id }}</p>
    <p><b>Status:</b> {{ $order->order_status }}</p>
    <p><b>Payment:</b> {{ $order->payment_status }}</p>
    <p><b>Delivery Address:</b> {{ $order->address->address_line }}, {{ $order->address->city }}, {{ $order->address->state }} - {{ $order->address->pincode }}</p>

    <h4 class="mt-3">Items</h4>
    <ul>
        @foreach($order->items as $item)
            <li>{{ $item->product->name }} - Quantity: {{ $item->quantity }} - Price: ₹{{ $item->price }}</li>
        @endforeach
    </ul>

    <a href="{{ route('home') }}" class="btn btn-primary mt-3">Back to Home</a>
</div>
@endsection
