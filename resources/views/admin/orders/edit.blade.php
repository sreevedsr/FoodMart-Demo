@extends('layouts.admin')

@section('title', 'Update Order')

@section('content')
    <div class="container mt-4">
        <h2>Update Order</h2>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form action="{{ route('orders.update', $order->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">Order ID</label>
                <input type="text" class="form-control" value="{{ $order->id }}" readonly>
            </div>

            <div class="mb-3">
                <label class="form-label">User ID</label>
                <input type="text" class="form-control" value="{{ $order->user_id }}" readonly>
            </div>

            <div class="mb-3">
                <label class="form-label">Address ID</label>
                <input type="text" class="form-control" value="{{ $order->address_id }}" readonly>
            </div>

            <div class="mb-3">
                <label class="form-label">Payment Status</label>
                <select name="payment_status" class="form-select" required>
                    @foreach(['Pending', 'Completed', 'Failed'] as $status)
                        <option value="{{ $status }}" {{ strtolower($order->payment_status) == strtolower($status) ? 'selected' : '' }}>
                            {{ $status }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Order Status</label>
                <select name="order_status" class="form-select" required>
                    @foreach(['Pending', 'Processing', 'Completed', 'Cancelled'] as $status)
                        <option value="{{ $status }}" {{ strtolower($order->order_status) == strtolower($status) ? 'selected' : '' }}>
                            {{ $status }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Total Amount</label>
                <input type="text" class="form-control" value="₹{{ $order->total_amount }}" readonly>
            </div>

            <button type="submit" class="btn btn-primary">Update Order</button>
        </form>
    </div>
@endsection