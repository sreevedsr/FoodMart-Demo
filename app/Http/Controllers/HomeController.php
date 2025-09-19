<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;


class HomeController extends Controller
{

public function index()
{
    $categories = Category::has('products')->get();
    $products = Product::all(); // ← define products
    return view('home', compact('categories', 'products'));
}
}

