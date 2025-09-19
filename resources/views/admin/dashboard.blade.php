@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    <h1 class="mb-4">Admin Dashboard</h1>

    <div class="row g-4">
        <!-- Sales Card -->
        <div class="col-md-3">
            <div class="card p-3">
                <h5>Total Sales</h5>
                <p class="stat-value text-primary">$42.8k</p>
                <small class="text-muted">78% of target 🚀</small>
            </div>
        </div>

        <!-- Products -->
        <div class="col-md-3">
            <div class="card p-3">
                <h5>Products</h5>
                <p class="stat-value text-success">{{ $productCount ?? 0 }}</p>
                <small class="text-muted">Active Products</small>
            </div>
        </div>

        <!-- Customers -->
        <div class="col-md-3">
            <div class="card p-3">
                <h5>Customers</h5>
                <p class="stat-value text-warning">{{ $customerCount ?? 0 }}</p>
                <small class="text-muted">Registered Users</small>
            </div>
        </div>

        <!-- Revenue -->
        <div class="col-md-3">
            <div class="card p-3">
                <h5>Revenue</h5>
                <p class="stat-value text-danger">${{ $revenue ?? '0.00' }}</p>
                <small class="text-muted">This Month</small>
            </div>
        </div>
    </div>

    <!-- Product Table -->
    <div class="card mt-4 p-3">
        <h5>Recent Products</h5>
        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Added On</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                <tr>
                    <td>{{ $product->name }}</td>
                    <td>${{ $product->price }}</td>
                    <td>{{ $product->stock }}</td>
                    <td>{{ $product->created_at->format('d M Y') }}</td>
                    <td>
                        <a href="{{ route('products.edit', $product->id) }}" class="btn btn-sm btn-purple">Edit</a>
                        <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="d-inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center text-muted">No products found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
