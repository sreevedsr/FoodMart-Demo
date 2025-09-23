<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Address;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    // Show checkout page
    public function index()
    {
        // Get the user's cart and items
        $cart = Cart::with('cartItems.product')->where('user_id', Auth::id())->first();
        $cartItems = $cart ? $cart->cartItems : collect();

        // Get the user's addresses
        $addresses = Address::where('user_id', Auth::id())->get();

        return view('checkout.index', compact('cartItems', 'addresses'));
    }
    // Add a new address
    public function addAddress(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'address_line' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'pincode' => 'required|string|max:10',
        ]);

        Address::create([
            'user_id' => Auth::id(),
            'name' => $request->name,
            'phone' => $request->phone,
            'address_line' => $request->address_line,
            'city' => $request->city,
            'state' => $request->state,
            'pincode' => $request->pincode,
        ]);

        return redirect()->back()->with('success', 'Address added successfully');
    }

    // Edit an existing address
    public function editAddress(Address $address, Request $request)
    {
        $this->authorize('update', $address); // Make sure user owns the address

        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'address_line' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'pincode' => 'required|string|max:10',
        ]);

        $address->update($request->all());

        return redirect()->back()->with('success', 'Address updated successfully');
    }

    // Place order (COD)
    public function placeOrder(Request $request)
    {
        // Validate that an address is selected
        $request->validate([
            'address_id' => 'required|exists:addresses,id',
        ]);

        $cart = Cart::where('user_id', Auth::id())
            ->with('cartItems.product')
            ->first();

        if (!$cart || $cart->cartItems->isEmpty()) {
            return redirect()->back()->with('error', 'Your cart is empty!');
        }

        $address = Address::find($request->address_id);

        // Here you would create the order in your orders table
        // For now, we'll just simulate order placement
        // Example: Order::create([...]);

        // Optionally, clear the cart after order
        $cart->cartItems()->delete();

        return redirect()->route('home')->with('success', 'Order placed successfully with Cash on Delivery!');
    }
}
