<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\ProductResource;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Requests\DestroyProductRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductController extends Controller
{
    public function index(): JsonResource
    {
        return ProductResource::collection(Product::all());
    }

    public function show(Product $product): ProductResource
    {
        return new ProductResource($product);
    }

    public function store(StoreProductRequest $request): ProductResource
    {
        $validated = $request->validated();

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('images', 'public');
        }

        $product = Product::create([
            'supplier_id' => Auth::id(),
            'name' => $validated['name'],
            'price' => $validated['price'],
            'description' => $validated['description'],
            'image' => $imagePath,
        ]);

        return new ProductResource($product);
    }

    public function update(UpdateProductRequest $request, Product $product): ProductResource
    {
        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::delete($product->image);
            }

            $product->image = $request->file('image')->store('images', 'public');
        }

        $product->update($request->safe()->only('name', 'price', 'description'));

        return new ProductResource($product);
    }

    public function destroy(DestroyProductRequest $request, Product $product): JsonResponse
    {
        if ($product->image) {
            Storage::delete($product->image);
        }

        $product->delete();

        return response()->json(null, 204);
    }
}
