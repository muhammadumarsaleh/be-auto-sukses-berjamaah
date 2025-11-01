<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::latest()->get();
        return view('pages.product.index', compact('products'));
    }

public function store(Request $request)
{
    $request->validate([
        'name' => 'required',
        'price' => 'required|numeric',
        'description' => 'nullable',
        'stock' => 'required|integer',
        'images.*' => 'image|mimes:jpg,jpeg,png|max:2048'
    ]);

    $product = Product::create($request->only('name', 'price', 'description', 'stock'));

    if ($request->hasFile('images')) {
        foreach ($request->file('images') as $file) {
            $path = $file->store('products', 'public');
            $product->images()->create(['image_path' => $path]);
        }
    }

    return response()->json(['success' => true]);
}


public function show($id)
{
    $product = Product::with('images')->findOrFail($id);
    return response()->json($product);
}


public function update(Request $request, Product $product)
{
    $product->update($request->only('name', 'price', 'description', 'stock'));

    if ($request->hasFile('images')) {
        foreach ($request->file('images') as $file) {
            $path = $file->store('products', 'public');
            $product->images()->create(['image_path' => $path]);
        }
    }

    return response()->json(['success' => true]);
}


public function destroy(Product $product)
{
    foreach ($product->images as $img) {
        Storage::disk('public')->delete($img->image_path);
        $img->delete();
    }

    $product->delete();
    return response()->json(['success' => true]);
}


    public function addImage(Request $request, Product $product)
{
    $request->validate(['images.*' => 'image|mimes:jpg,jpeg,png|max:2048']);

    foreach ($request->file('images') as $file) {
        $path = $file->store('products', 'public');
        $product->images()->create(['image_path' => $path]);
    }

    return response()->json(['success' => true]);
}

    public function destroyImage($id) {
        $img = ProductImage::findOrFail($id);
        \Storage::disk('public')->delete($img->image_path);
        $img->delete();
        return response()->json(['success'=>true]);
    }



}
