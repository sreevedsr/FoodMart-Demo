@extends('layouts.admin')

@section('title', 'Manage Products')

@section('content')
    <div class="page-header">
        <h1>Manage Products</h1>
    </div>

    <!-- Add Product Button -->
    <a href="{{ route('products.create') }}" class="button-link">Add Product</a>

    <!-- Products Table -->
    <div class="card mt-4">
        <table class="products-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Category</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                <tr>
                    <td>{{ $product->name }}</td>
                    <td>${{ $product->price }}</td>
                    <td>{{ $product->category->name ?? 'Uncategorized' }}</td>
                    <td>
                        <a href="{{ route('products.edit', $product->id) }}" class="button-link small-button">Edit</a>
                        <form method="POST" action="{{ route('products.destroy', $product->id) }}" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="button-link small-button delete-button">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4">No products found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
