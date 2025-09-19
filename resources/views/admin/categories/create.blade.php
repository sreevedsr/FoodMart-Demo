@extends('layouts.admin')

@section('title', 'Add Category')

@section('content')
    <h1>Add Category</h1>
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('categories.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" id="name" name="name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea id="description" name="description" class="form-control" rows="3"></textarea>
        </div>

        <div class="mb-3">
            <label for="image" class="custom-file-upload">Category Image</label>
            <input type="file" id="image" name="image" class="form-control" accept="image/*" required>
        </div>

        <button type="submit" class="btn btn-primary">Save</button>
    </form>

@endsection