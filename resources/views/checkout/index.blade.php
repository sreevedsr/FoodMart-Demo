@extends('layouts.app')
<style>
  .selectable-address {
    cursor: pointer;
    transition: background-color 0.2s ease;
  }

  .selectable-address:hover {
    background-color: rgba(226, 227, 228, 0.95);
    /* light gray highlight on hover */
  }
</style>
@section('content')
  <div class="container mt-5">
    <h2>Checkout</h2>

    <h4 class="mt-4">Delivery Address</h4>

    @if($addresses->isEmpty())
      <p>No saved addresses. Please add a new address to continue.</p>
      <form id="addAddressForm" action="{{ route('checkout.address.add') }}" method="POST">
        @csrf
        <input type="text" name="name" placeholder="Full Name" class="form-control mb-2" required>
        <input type="text" name="phone" placeholder="Phone" class="form-control mb-2" required>
        <input type="text" name="address_line" placeholder="Address Line" class="form-control mb-2" required>
        <input type="text" name="city" placeholder="City" class="form-control mb-2" required>
        <input type="text" name="state" placeholder="State" class="form-control mb-2" required>
        <input type="text" name="pincode" placeholder="Pincode" class="form-control mb-2" required>
        <button class="btn btn-success mt-2">Add Address</button>
      </form>
    @else
      @php
        // Use first address as default (or you could mark one in DB as default)
        $defaultAddress = $addresses->first();
      @endphp

      <!-- Default Address Section -->
      <div class="card p-3 mb-3" id="defaultAddressDiv">
        <p><b>{{ $defaultAddress->name }}</b> - {{ $defaultAddress->phone }}</p>
        <p>{{ $defaultAddress->address_line }}, {{ $defaultAddress->city }}, {{ $defaultAddress->state }} -
          {{ $defaultAddress->pincode }}
        </p>
        <button class="btn btn-sm btn-link text-decoration-none text-primary" data-bs-toggle="modal"
          data-bs-target="#changeAddressModal">Change</button>
      </div>

      <!-- Change Address Modal -->
      <div class="modal fade" id="changeAddressModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Choose Another Address</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
              @foreach($addresses as $address)
                <div class="card mb-2 p-3 selectable-address" data-id="{{ $address->id }}">
                  <p><b>{{ $address->name }}</b> - {{ $address->phone }}</p>
                  <p>{{ $address->address_line }}, {{ $address->city }}, {{ $address->state }} - {{ $address->pincode }}</p>
                  <button class="btn btn-sm btn-warning" type="button" data-bs-toggle="modal"
                    data-bs-target="#editAddressModal{{ $address->id }}">Edit</button>
                </div>
              @endforeach
            </div>
          </div>
        </div>
      </div>
      @foreach($addresses as $address)
        <div class="modal fade" id="editAddressModal{{ $address->id }}" tabindex="-1">
          <div class="modal-dialog">
            <div class="modal-content">
              <form action="{{ route('checkout.address.edit', $address) }}" method="POST">
                @csrf
                <div class="modal-header">
                  <h5 class="modal-title">Edit Address</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                  <input type="text" name="name" value="{{ $address->name }}" class="form-control mb-2" required>
                  <input type="text" name="phone" value="{{ $address->phone }}" class="form-control mb-2" required>
                  <input type="text" name="address_line" value="{{ $address->address_line }}" class="form-control mb-2"
                    required>
                  <input type="text" name="city" value="{{ $address->city }}" class="form-control mb-2" required>
                  <input type="text" name="state" value="{{ $address->state }}" class="form-control mb-2" required>
                  <input type="text" name="pincode" value="{{ $address->pincode }}" class="form-control mb-2" required>
                </div>
                <div class="modal-footer">
                  <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      @endforeach
      <!-- Place Order Form -->
      <form action="{{ route('checkout.placeOrder') }}" method="POST" id="placeOrderForm">
        @csrf
        <input type="hidden" name="address_id" id="selectedAddressId" value="{{ $defaultAddress->id }}">

        <h4 class="mt-4">Payment Method</h4>
        <p><b>Cash on Delivery (COD) only</b></p>

        <button class="btn btn-purple mt-3" type="submit">Place Order</button>
      </form>
    @endif
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const addressCards = document.querySelectorAll('.selectable-address');
      const hiddenInput = document.getElementById('selectedAddressId');
      const defaultAddressDiv = document.getElementById('defaultAddressDiv');
      const modalEl = document.getElementById('changeAddressModal');

      addressCards.forEach(card => {
        card.addEventListener('click', function (e) {
          // If click started on a button/link or any element that toggles a modal,
          // do nothing here so Bootstrap's delegated handler can open the modal.
          if (e.target.closest('button, a, [data-bs-toggle]')) return;

          const id = card.getAttribute('data-id');
          hiddenInput.value = id;

          defaultAddressDiv.querySelector('p:nth-of-type(1)').innerHTML =
            card.querySelector('p:nth-of-type(1)').innerHTML;
          defaultAddressDiv.querySelector('p:nth-of-type(2)').innerHTML =
            card.querySelector('p:nth-of-type(2)').innerHTML;

          // hide the change address modal
          const modalInstance = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
          modalInstance.hide();
        });
      });
    });
  </script>



@endsection