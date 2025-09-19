@extends('layouts.admin')

@section('title', 'Add Product')

@section('content')
    <div class="page-header">
        <h1>Add Product</h1>
    </div>
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" class="form-card">
        @csrf

        <!-- Product Name -->
        <div class="form-group">
            <input type="text" name="name" placeholder="Product Name" value="{{ old('name') }}" required>
        </div>

        <!-- Price -->
        <div class="form-group">
            <input type="number" name="price" placeholder="Price" value="{{ old('price') }}" step="0.01" required>
        </div>

        <!-- Category -->
        <div class="form-group">
            <select name="category_id">
                <option value="">Select Category</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Image Upload -->
        <div class="form-group">
            <label class="custom-file-upload">
                Choose File
                <input type="file" name="image" id="imageInput" required>
                <div class="file-name" id="fileName">No file chosen</div>
            </label>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="button-link">Add Product</button>
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
@endsection

@push('scripts')
    <script>
        const input = document.getElementById('imageInput');
        const fileName = document.getElementById('fileName');

        input.addEventListener('change', function () {
            if (this.files.length > 0) {
                fileName.textContent = this.files[0].name;
            } else {
                fileName.textContent = "No file chosen";
            }
        });
    </script>
@endpush