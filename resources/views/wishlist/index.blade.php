@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <h2>My Wishlist</h2>
        <div class="row">
            @forelse($wishlistItems as $item)
                <div class="col-md-3 mb-3">
                    <div class="product-item">
                        <figure>
                            <a href="{{ route('products.show', $item->product->id) }}" title="{{ $item->product->name }}">
                                <img src="{{ asset('storage/' . $item->product->image) }}" class="tab-image">
                            </a>
                        </figure>
                        <div class="card-body">
                            <h5 class="card-title">{{ $item->product->name }}</h5>
                            <p class="card-text">${{ $item->product->price }}</p>
                            <form action="{{ route('wishlist.destroy', $item->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm">Remove</button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <p>You have no items in your wishlist.</p>
            @endforelse
        </div>
    </div>
@endsection