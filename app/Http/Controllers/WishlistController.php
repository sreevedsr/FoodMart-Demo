<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Wishlist;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function index()
    {
        $wishlistItems = Auth::user()->wishlist()->with('product')->get();
        return view('wishlist.index', compact('wishlistItems'));
    }

    public function store(Request $request, $productId)
    {
        $user = Auth::user();
        $wishlist = $user->wishlist()->where('product_id', $productId)->first();

        if ($wishlist) {
            $wishlist->delete();
            return response()->json(['status' => 'removed']);
        } else {
            Wishlist::create([
                'user_id' => $user->id,
                'product_id' => $productId
            ]);
            return response()->json(['status' => 'added']);
        }
    }

    public function destroy($id)
    {
        $wishlistItem = Wishlist::findOrFail($id);
        $wishlistItem->delete();

        return back()->with('success', 'Product removed from wishlist!');
    }
}
