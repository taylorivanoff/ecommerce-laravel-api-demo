<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        return Product::with('supplier')->get();
    }

    public function show($id)
    {
        $product = Product::with('supplier')->findOrFail($id);

        return response()->json($product);
    }

    public function store(Request $request)
    {
        if ($request->user()->role !== 'supplier') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('images', 'public');
        }

        $product = Product::create([
            'supplier_id' => Auth::id(),
            'name' => $request->name,
            'price' => $request->price,
            'description' => $request->description,
            'image' => $imagePath,
        ]);

        return response()->json($product, 201);
    }

    public function update(Request $request, Product $product)
    {
        if ($request->user()->role !== 'supplier') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::delete($product->image);
            }
            $product->image = $request->file('image')->store('images', 'public');
        }

        $product->update($request->only('name', 'price', 'description'));

        return response()->json($product);
    }

    public function destroy(Request $request, $id)
    {
        if ($request->user()->role !== 'supplier') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $product = Product::findOrFail($id);

        if ($product->image) {
            Storage::delete($product->image);
        }

        $product->delete();

        return response()->json(null, 204);
    }
}
