@extends('layouts.admin')

@section('title', 'Edit Category')

@section('content')
    <h1>Edit Category</h1>

    <form action="{{ route('categories.update', $category->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div>
            <label>Name</label>
            <input type="text" name="name" value="{{ $category->name }}" required>
        </div>
        <div>
            <label>Description</label>
            <textarea name="description">{{ $category->description }}</textarea>
        </div>
        <button type="submit">Update</button>
    </form>
@endsection
