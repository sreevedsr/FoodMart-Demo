<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */

public function boot()
{
    view()->composer('*', function ($view) {
        $cartCount = 0;
        $cartTotal = 0;

        if (Auth::check()) {
            $cart = Cart::with('items.product')
                        ->where('user_id', Auth::id())
                        ->first();

            if ($cart) {
                $cartCount = $cart->items->sum('quantity');
                $cartTotal = $cart->items->sum(fn($item) => $item->quantity * $item->product->price);
            }
        }

        $view->with([
            'cartCount' => $cartCount,
            'cartTotal' => number_format($cartTotal, 2)
        ]);
    });
}

}
