@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h2>Order Tracking</h2>

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
</div>
@endsection
