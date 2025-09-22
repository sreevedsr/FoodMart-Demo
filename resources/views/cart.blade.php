@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <div class="card">
            <div class="row">
                <!-- Cart Items Section -->
                <div class="col-md-8 cart">
                    <div class="title">
                        <div class="row">
                            <div class="col">
                                <h4><b>Shopping Cart</b></h4>
                            </div>
                            <div class="col align-self-center text-right text-muted">{{ $cartItems->count() }} items</div>
                        </div>
                    </div>

                    @foreach($cartItems as $item)
                        <div class="row border-top border-bottom main align-items-center" data-id="{{ $item->id }}">
                            <div class="col-2">
                                <img src="{{ asset('storage/' . ($item->product->image ?? 'placeholder.png')) }}"
                                    class="tab-image">
                            </div>
                            <div class="col">
                                <div class="row text-muted">{{ $item->product->name }}</div>
                                <div class="row">{{ $item->product->description ?? '' }}</div>
                            </div>
                            <div class="col">
                                {{-- <a href="#" class="decrement">-</a>
                                <span class="border px-2 quantity">{{ $item->quantity }}</span>
                                <a href="#" class="increment">+</a> --}}
                                <div class="input-group product-qty" data-id="{{ $item->id }}">
                                    <button type="button" class="quantity-left-minus btn btn-danger btn-number"
                                        data-type="minus">
                                        <svg width="16" height="16">
                                            <use xlink:href="#minus"></use>
                                        </svg>
                                    </button>
                                    <span class="border px-2 quantity">{{ $item->quantity }}</span>
                                    <button type="button" class="quantity-right-plus btn btn-success btn-number"
                                        data-type="plus">
                                        <svg width="16" height="16">
                                            <use xlink:href="#plus"></use>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            <div class="col">
                                ₹<span class="item-total">{{ $item->product->price * $item->quantity }}</span>
                                <span class="close remove-item">&#10005;</span>
                            </div>
                        </div>
                    @endforeach

                    <div class="back-to-shop mt-3">
                        <a href="{{ route('home') }}">&leftarrow;
                            <span class="text-muted">Back to shop</span></a>
                    </div>
                </div>

                <!-- Summary Section -->
                <div class="col-md-4 summary">
                    <div>
                        <h5><b>Summary</b></h5>
                    </div>
                    <hr>
                    <div class="row mb-3">
                        <div class="col" style="padding-left:0;">ITEMS <span
                                id="mini-cart-items">{{ $cartItems->sum('quantity') }}</span></div>
                        <div class="col text-right">₹<span
                                id="mini-cart-subtotal">{{ $cartItems->sum(fn($i) => $i->product->price * $i->quantity) }}</span>
                        </div>
                    </div>
                    <form>
                        <p>SHIPPING</p>
                        <select>
                            <option class="text-muted">Standard Delivery - ₹50</option>
                        </select>
                        <p>GIVE CODE</p>
                        <input id="code" placeholder="Enter your code">
                    </form>
                    <div class="row mt-3" style="border-top: 1px solid rgba(0,0,0,.1); padding: 2vh 0;">
                        <div class="col">TOTAL PRICE</div>
                        <div class="col text-right">₹<span
                                id="mini-cart-total">{{ $cartItems->sum(fn($i) => $i->product->price * $i->quantity + 50) }}</span>
                        </div>
                    </div>
                    <button class="btn btn-purple mt-3">CHECKOUT</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('styles')
    <style>
        .cart .title {
            margin-bottom: 20px;
        }

        .cart .main {
            padding: 15px 0;
        }

        .cart .main .col-2 img {
            max-width: 100%;
            border-radius: 10px;
        }

        .cart .decrement,
        .cart .increment {
            cursor: pointer;
            padding: 5px 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin: 0 5px;
            text-decoration: none;
            color: #333;
        }

        .cart .decrement:hover,
        .cart .increment:hover {
            background-color: #f0f0f0;
        }

        .cart .remove-item {
            cursor: pointer;
            color: red;
            margin-left: 10px;
            font-size: 18px;
        }

        .summary {
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 10px;
        }

        .summary h5 {
            margin-bottom: 15px;
        }

        .summary select,
        .summary input {
            width: 100%;
            padding: 8px 10px;
            margin-bottom: 15px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        .summary .btn-purple {
            width: 100%;
            background-color: #6f42c1;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            font-weight: bold;
            cursor: pointer;
        }

        .summary .btn-purple:hover {
            background-color: #5931a0;
        }

        .back-to-shop a {
            text-decoration: none;
            color: #555;
            display: inline-flex;
            align-items: center;
        }

        .back-to-shop a span {
            margin-left: 5px;
        }

        /* Mobile adjustments */
        @media (max-width: 768px) {
            .cart .main {
                flex-wrap: wrap;
            }

            .cart .col {
                margin-top: 10px;
            }

            .summary {
                margin-top: 20px;
            }
        }
    </style>
@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.iconify.design/3/3.1.0/iconify.min.js"></script>
    <script>
        $(document).ready(function () {
            // Increment quantity
            $('.increment').click(function (e) {
                e.preventDefault();
                let row = $(this).closest('.main');
                let id = row.data('id');
                let qtySpan = row.find('.quantity');
                let qty = parseInt(qtySpan.text()) + 1;

                $.post('{{ route("cart.update") }}', {
                    _token: '{{ csrf_token() }}',
                    cart_id: id,
                    quantity: qty
                }, function (res) {
                    qtySpan.text(res.quantity);
                    row.find('.col:last').contents().first()[0].textContent = res.total_price;
                });
            });

            // Remove item
            $('.remove-item').click(function (e) {
                e.preventDefault();
                let row = $(this).closest('.main');
                let id = row.data('id');

                $.post('{{ route("cart.remove") }}', {
                    _token: '{{ csrf_token() }}',
                    cart_id: id
                }, function () {
                    row.remove();
                });
            });
        });
        $(document).ready(function () {
            $('.add-to-cart').click(function (e) {
                e.preventDefault();
                let productId = $(this).data('product');

                $.ajax({
                    url: '{{ route("cart.add") }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        product_id: productId
                    },
                    success: function (response) {
                        let cartItem = `<li class="list-group-item">${response.product.name} - ${response.product.price}</li>`;
                        $('#cart-items').append(cartItem);
                        alert('Product added to cart!');
                    },
                    error: function (xhr) {
                        alert('Something went wrong!');
                    }
                });
            });
        });
        $(document).ready(function () {
            $('.quantity-left-minus, .quantity-right-plus').click(function (e) {
                e.preventDefault();

                let $btn = $(this);
                let action = $btn.data('type'); // plus or minus
                let $parent = $btn.closest('.product-qty');
                let quantitySpan = $parent.find('.quantity');
                let itemId = $parent.data('id');

                $.ajax({
                    url: '{{ route("cart.update") }}',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        id: itemId,
                        action: action
                    },
                    success: function (response) {
                        if (response.status === 'success') {
                            quantitySpan.text(response.quantity);
                            $parent.closest('.row').find('.item-total').text(response.itemTotal);
                        } else if (response.status === 'deleted') {
                            $parent.closest('.main').remove();
                        }

                        // Update top cart dropdown
                        $('#cart-total').text(response.cartTotal);
                        $('#cart-count').text(response.cartCount);

                        // Update mini cart summary
                        $('#mini-cart-items').text(response.cartCount);
                        $('#mini-cart-subtotal').text(response.cartTotal);
                        $('#mini-cart-total').text(response.totalWithShipping);
                    },
                    error: function (err) {
                        console.log(err);
                    }
                });
            });
        });

    </script>
@endsection