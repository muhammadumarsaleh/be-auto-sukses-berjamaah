<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ShopController extends Controller
{
    // 🛍️ halaman katalog
    public function index()
    {
        $products = Product::latest()->get();
        return view('pages.shop.catalog', compact('products'));
    }

    // 📄 detail produk
    public function show($id)
    {
        $product = Product::findOrFail($id);
        return view('pages.shop.show', compact('product'));
    }

    // 🛒 tambah ke keranjang (pakai session dulu)
    public function addToCart(Request $request)
    {
        $product = Product::findOrFail($request->product_id);
        $qty = max(1, (int)$request->qty);

        $cart = Session::get('cart', []);

        if (isset($cart[$product->id])) {
            $cart[$product->id]['qty'] += $qty;
        } else {
            $cart[$product->id] = [
                'name' => $product->name,
                'price' => $product->price,
                'qty' => $qty,
                'image' => $product->image,
            ];
        }

        Session::put('cart', $cart);

        return response()->json([
            'success' => true,
            'message' => "{$product->name} berhasil ditambahkan ke keranjang."
        ]);
    }
}
