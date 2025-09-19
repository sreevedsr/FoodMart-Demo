@extends('layouts.admin')

@section('title', 'Manage Categories')

@section('content')
    <div class="page-header">
        <h1>Manage Categories</h1>
    </div>

    <a href="{{ route('categories.create') }}" class="button-link">Add Category</a>

    <div class="card mt-4">
        <table class="products-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $category)
                <tr>
                    <td>{{ $category->name }}</td>
                    <td>{{ $category->description }}</td>
                    <td>
                        <a href="{{ route('categories.edit', $category->id) }}" class="button-link small-button">Edit</a>
                        <form action="{{ route('categories.destroy', $category->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('Are you sure?')" class="button-link small-button delete-button">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4">No categories found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
