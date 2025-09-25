@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <div class="card" style="border: 0;">
            <div class="row">
                <!-- Cart Items Section -->
                <div class="col-md-8 cart">
                    <div class="title">
                        <div class="row">
                            <div class="col">
                                <h4><b style="margin: 20px">Shopping Cart</b></h4>
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
                        <p>COUPON CODE</p>
                        <input id="code" placeholder="Enter your code manually" class="form-control mb-2">
                        <div id="coupon-cards" class="d-flex flex-wrap gap-2 mb-2">
                            <!-- Coupon cards will be loaded here dynamically -->
                        </div>
                        <button id="apply-code" class="btn btn-secondary btn-sm mb-3">Apply</button>
                    </form>
                    <div class="row mt-3" style="border-top: 1px solid rgba(0,0,0,.1); padding: 2vh 0;">
                        <div class="col">TOTAL PRICE</div>
                        <div class="col text-right">₹<span
                                id="mini-cart-total">{{ $cartItems->sum(fn($i) => $i->product->price * $i->quantity) }}</span>
                        </div>
                    </div>
                    <form action="{{ route('checkout') }}" method="GET">
                        <button type="submit" class="btn btn-purple mt-3">CHECKOUT</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
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
                let id = row.data('id'); // this should be the CartItem id

                $.post('{{ route("cart.remove") }}', {
                    _token: '{{ csrf_token() }}',
                    cart_item_id: id // <-- match the controller
                }, function (res) {
                    if (res.success) {
                        row.remove();
                        // optionally update cart totals
                        $('#cart-total').text(res.cartTotal);
                        $('#cart-count').text(res.cartCount);
                    }
                }).fail(function (xhr) {
                    console.log(xhr.responseText);
                    alert('Error removing item.');
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
        $(document).ready(function () {
            const shipping = 50;
            let appliedCoupon = null;

            // Fetch coupons from backend
            $.get('/coupons/active', function (coupons) {
                coupons.forEach(c => {
                    let text = c.type === 'flat' ? `₹${c.amount} off` : `${c.amount}% off`;
                    let card = `<div class="coupon-card p-2 border" data-code="${c.code}" data-type="${c.type}" data-amount="${c.amount}" data-min-total="${c.min_total || 0}" style="cursor:pointer;">
                                            <strong>${c.code}</strong><br><small>${text}</small>
                                        </div>`;
                    $('#coupon-cards').append(card);
                });
            });

            function updateTotal() {
                let subtotal = parseFloat($('#mini-cart-subtotal').text());
                let total = subtotal + shipping;

                if (appliedCoupon) {
                    let minTotal = parseFloat(appliedCoupon.min_total);
                    if (total >= minTotal) {
                        if (appliedCoupon.type === 'flat') {
                            total -= parseFloat(appliedCoupon.amount);
                        } else if (appliedCoupon.type === 'percent') {
                            total -= (total * parseFloat(appliedCoupon.amount) / 100);
                        }
                    } else {
                        alert(`Coupon requires a minimum total of ₹${minTotal}`);
                        appliedCoupon = null;
                        $('.coupon-card').removeClass('bg-success text-white');
                    }
                }

                $('#mini-cart-total').text(total.toFixed(2));
            }

            // Click coupon card
            $(document).on('click', '.coupon-card', function () {
                $('.coupon-card').removeClass('bg-success text-white');
                $(this).addClass('bg-success text-white');

                appliedCoupon = {
                    code: $(this).data('code'),
                    type: $(this).data('type'),
                    amount: $(this).data('amount'),
                    min_total: $(this).data('min-total')
                };

                $('#code').val(''); // clear manual input
                updateTotal();
            });

            // Manual code apply
            $('#apply-code').click(function () {
                let code = $('#code').val().trim().toUpperCase();
                let card = $(`.coupon-card[data-code="${code}"]`);

                if (card.length) {
                    card.click(); // trigger card selection
                } else {
                    alert('Invalid coupon code');
                }
            });

            // Update total on quantity change
            // (Include this in your existing AJAX success after updating quantities)
            function refreshTotalWithShipping() {
                updateTotal();
            }
        });
    </script>
@endsection