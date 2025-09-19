{{-- resources/views/cart.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h2 class="text-center mb-4">Products</h2>
    <div class="row">
        @foreach($products as $product)
        <div class="col-md-4 mb-4">
            <div class="card p-3">
                <img src="{{ $product->image }}" class="card-img-top" alt="{{ $product->name }}">
                <div class="card-body text-center">
                    <h5 class="card-title">{{ $product->name }}</h5>
                    <p class="card-text">Price: ₹{{ $product->price }}</p>
                    <a href="#" class="nav-link add-to-cart btn btn-primary" data-product="{{ $product->id }}">
                        Add to Cart <iconify-icon icon="uil:shopping-cart"></iconify-icon>
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <h3 class="mt-5 text-center">Cart Items</h3>
    <ul id="cart-items" class="list-group">
        {{-- Cart items will be dynamically appended here --}}
    </ul>
</div>
@endsection

@section('scripts')
<script src="https://code.iconify.design/3/3.1.0/iconify.min.js"></script>
<script>
    $(document).ready(function() {
        $('.add-to-cart').click(function(e) {
            e.preventDefault();
            let productId = $(this).data('product');

            $.ajax({
                url: '{{ route("cart.add") }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    product_id: productId
                },
                success: function(response) {
                    let cartItem = `<li class="list-group-item">${response.product.name} - ₹${response.product.price}</li>`;
                    $('#cart-items').append(cartItem);
                    alert('Product added to cart!');
                },
                error: function(xhr) {
                    alert('Something went wrong!');
                }
            });
        });
    });
</script>
@endsection
