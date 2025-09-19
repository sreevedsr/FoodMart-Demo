{{-- resources/views/cart.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h3 class="text-center">Cart Items</h3>
    <ul id="cart-items" class="list-group">
    @foreach($cartItems as $item)
        <li class="list-group-item">
            {{ $item->product->name }} - ₹{{ $item->product->price }} x {{ $item->quantity }}
        </li>
    @endforeach
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
