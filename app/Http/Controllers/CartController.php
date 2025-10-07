<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use App\Models\Coupon;


class CartController extends Controller
{
    public function add(Request $request)
    {
        $product = Product::find($request->product_id);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        $requestedQuantity = (int) $request->input('quantity', 1);

        if (Auth::check()) {
            $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);

            $cartItem = CartItem::where('cart_id', $cart->id)
                ->where('product_id', $product->id)
                ->first();

            if ($cartItem) {
                $cartItem->quantity += $requestedQuantity;
                $cartItem->save();
            } else {
                $cartItem = CartItem::create([
                    'cart_id' => $cart->id,
                    'product_id' => $product->id,
                    'quantity' => $requestedQuantity
                ]);
            }

            $cart->load('items.product');
            $cartCount = $cart->items->sum('quantity');
            $cartTotal = $cart->items->sum(fn($item) => $item->quantity * $item->product->price);

        } else {
            $cart = session()->get('cart.items', []);

            $existingItemKey = collect($cart)->search(fn($item) => $item['product_id'] == $product->id);

            if ($existingItemKey !== false) {
                $cart[$existingItemKey]['quantity'] += $requestedQuantity;
            } else {
                $cart[] = [
                    'product_id' => $product->id,
                    'quantity' => $requestedQuantity
                ];
            }

            session(['cart.items' => $cart]);
            $cartCount = collect($cart)->sum('quantity');
            $cartTotal = collect($cart)->sum(function ($item) {
                $product = Product::find($item['product_id']);
                return $product ? $product->price * $item['quantity'] : 0;
            });
        }

        return response()->json([
            'product' => [
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => $requestedQuantity
            ],
            'cartCount' => $cartCount,
            'cartTotal' => number_format($cartTotal, 2)
        ]);
    }



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

            if ($request->action === 'plus') {
                $cartItem->quantity++;
                $cartItem->save();
            } elseif ($request->action === 'minus') {
                if ($cartItem->quantity > 1) {
                    $cartItem->quantity--;
                    $cartItem->save();
                } else {
                    $cartItem->delete();
                }
            }

            $cartItems = CartItem::with('product')
                ->whereHas('cart', fn($q) => $q->where('user_id', Auth::id()))
                ->get();

            $cartTotal = $cartItems->sum(fn($i) => $i->quantity * $i->product->price);
            $cartCount = $cartItems->sum('quantity');
            $totalWithShipping = $cartTotal > 0 ? $cartTotal + 50 : 0;

            if (!$cartItem->exists) {
                return response()->json([
                    'status' => 'deleted',
                    'itemId' => $request->id,
                    'cartTotal' => $cartTotal,
                    'cartCount' => $cartCount,
                    'totalWithShipping' => $totalWithShipping
                ]);
            }

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
    public function activeCoupons()
    {
        $coupons = Coupon::where('active', 1)->get();
        return response()->json($coupons);
    }

    public function remove(Request $request)
    {
        $cart = Cart::where('user_id', Auth::id())->first();

        if (!$cart) {
            return response()->json(['error' => 'Cart not found'], 404);
        }

        $itemId = $request->input('cart_item_id');
        $item = $cart->items()->find($itemId);

        if (!$item) {
            return response()->json(['error' => 'Item not found in cart'], 404);
        }

        $item->delete();

        $cartItems = $cart->items()->with('product')->get();
        $cartTotal = $cartItems->sum(fn($i) => $i->quantity * $i->product->price);
        $cartCount = $cartItems->sum('quantity');

        return response()->json([
            'success' => true,
            'cartTotal' => $cartTotal,
            'cartCount' => $cartCount
        ]);
    }


}


