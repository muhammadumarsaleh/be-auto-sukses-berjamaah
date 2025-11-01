<?php

namespace App\Http\Controllers;

use App\Models\Product;

class ShopController extends Controller
{
    public function index()
    {
        $products = Product::with('images')->latest()->get();
        return view('pages.shop.catalog', compact('products'));
    }

    public function show($id)
    {
        $product = Product::with('images')->findOrFail($id);
        return view('pages.shop.show', compact('product'));
    }
}
