<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class HomeController extends Controller
{
    public function index()
    {
        // Fetch categories with at least one product
        $categories = Category::has('products')->get();

        return view('home', compact('categories'));
    }
}

