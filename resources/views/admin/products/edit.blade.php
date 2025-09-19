@extends('layouts.admin')

@section('title', 'Edit Product')

@section('content')
<div class="login-container">
    <h1>Edit Product</h1>

    <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- Product Name -->
        <input type="text" name="name" placeholder="Product Name" value="{{ old('name', $product->name) }}" required>

        <!-- Product Price -->
        <input type="number" name="price" placeholder="Price" value="{{ old('price', $product->price) }}" step="0.01" required>

        <!-- Product Category -->
        <select name="category_id" required>
            <option value="">Select Category</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}" {{ (old('category_id', $product->category_id) == $category->id) ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>

        <!-- Product Image -->
        <input type="file" name="image">
        @if($product->image)
            <p>Current Image:</p>
            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" width="100">
        @endif

        <!-- Submit Button -->
        <button type="submit" class="button-link">Update Product</button>
    </form>

    <!-- Display Validation Errors -->
    @if ($errors->any())
        <div class="error-message">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
</div>
@endsection
