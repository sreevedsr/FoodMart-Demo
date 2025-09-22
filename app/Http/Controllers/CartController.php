<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;


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

        // Calculate updated cart totals
        $cart->load('items.product');
        $cartCount = $cart->items->sum('quantity');
        $cartTotal = $cart->items->sum(fn($item) => $item->quantity * $item->product->price);

        return response()->json([
            'product' => [
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => $cartItem->quantity
            ],
            'cartCount' => $cartCount,
            'cartTotal' => number_format($cartTotal, 2)
        ]);
    }


    // Show cart items
    public function show()
    {
        $cart = Cart::with('items.product')->where('user_id', Auth::id())->first();

        $cartItems = $cart ? $cart->items : collect();

        return view('cart', compact('cartItems'));
    }


    public function updateQuantity(Request $request)
    {
        try {

            $cartItem = CartItem::with('product')->findOrFail($request->id);

            if ($request->action == 'plus') {
                $cartItem->quantity++;
            } elseif ($request->action == 'minus') {
                if ($cartItem->quantity > 1) {
                    $cartItem->quantity--;
                } else {
                    $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);
                    $cartId = $cart->id;

                    $cartItem->delete();
                    $cartItems = CartItem::with('product')
                        ->whereHas('cart', fn($q) => $q->where('user_id', Auth::id()))
                        ->get();
                    $cartTotal = DB::table('cart_items')
                        ->join('products', 'cart_items.product_id', '=', 'products.id')
                        ->where('cart_items.cart_id', $cartId)
                        ->sum(DB::raw('cart_items.quantity * products.price'));
                    $cartCount = $cartItems->sum('quantity');
                    $totalWithShipping = $cartTotal > 0 ? $cartTotal : 0;

                    return response()->json([
                        'status' => 'deleted',
                        'itemId' => $request->id,
                        'cartTotal' => $cartTotal,
                        'cartCount' => $cartCount,
                        'totalWithShipping' => $totalWithShipping
                    ]);
                }
            }

            $cartItem->save();

            $cartItems = CartItem::with('product')->get();
            $cartTotal = $cartItems->sum(fn($i) => $i->quantity * $i->product->price);
            $cartCount = $cartItems->sum('quantity');
            $totalWithShipping = $cartTotal > 0 ? $cartTotal : 0;

            return response()->json([
                'status' => 'success',
                'quantity' => $cartItem->quantity,
                'itemTotal' => $cartItem->quantity * $cartItem->product->price,
                'itemId' => $cartItem->id,
                'cartTotal' => $cartTotal,
                'cartCount' => $cartCount,
                'totalWithShipping' => $totalWithShipping
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

}

