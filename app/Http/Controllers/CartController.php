<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    // Add product to cart
    public function add(Request $request)
    {
        $product = Product::find($request->product_id);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        // Get or create cart for logged-in user
        $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);

        // Check if item already exists
        $cartItem = CartItem::where('cart_id', $cart->id)
                            ->where('product_id', $product->id)
                            ->first();

        if ($cartItem) {
            $cartItem->quantity++;
            $cartItem->save();
        } else {
            $cartItem = CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $product->id,
                'quantity' => 1
            ]);
        }

        return response()->json([
            'product' => [
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => $cartItem->quantity
            ]
        ]);
    }

    // Show cart items
    public function show()
    {
        $cart = Cart::with('items.product')->where('user_id', Auth::id())->first();

        $cartItems = $cart ? $cart->items : [];

        return view('cart', compact('cartItems'));
    }
}

