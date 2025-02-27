<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\CartResource;
use App\Http\Requests\AddCartRequest;
use App\Http\Requests\RemoveCartRequest;
use App\Http\Resources\CartItemResource;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Resources\Json\JsonResource;

class CartController extends Controller
{
    public function index(Request $request): JsonResource
    {
        $cart = Cart::where('user_id', $request->user()->id)
            ->with('product.supplier')
            ->get();

        return CartItemResource::collection($cart);
    }

    public function add(AddCartRequest $request): CartItemResource
    {
        $validated = $request->validated();

        $user = $request->user();

        $cartItem = Cart::where('user_id', $user->id)
            ->where('product_id', $validated['product_id'])
            ->first();

        if (!$cartItem) {
            $cartItem = Cart::create([
                'user_id' => $user->id,
                'product_id' => $validated['product_id'],
                'quantity' => $validated['quantity'],
            ]);
        }

        $cartItem->quantity += $validated['quantity'];

        $cartItem->save();

        return new CartItemResource($cartItem);
    }

    public function remove(RemoveCartRequest $request)
    {
        $validated = $request->validated();

        $user = $request->user();

        $cartItem = Cart::where('user_id', $user->id)
            ->where('product_id', $validated['product_id'])
            ->first();

        if (!$cartItem) {
            throw new ModelNotFoundException('Cart item not found');
        }

        $cartItem->quantity -= $validated['quantity'];

        if ($cartItem->quantity <= 0) {
            $cartItem->delete();

            return response()->json(null, 204);
        }

        $cartItem->save();

        return new CartItemResource($cartItem);
    }
}
