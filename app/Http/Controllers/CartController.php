<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;
use App\Models\Product;

class CartController extends Controller
{
    public function index(Request $request)
    {
        $cartItems = Cart::where('user_id', $request->user()->id)->with('product.supplier')->get();

        return response()->json($cartItems, 200);
    }

    public function add(Request $request)
    {
        $validatedData = $request->validate([
            'product_id' => 'required|integer|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $user = $request->user();

        $cartItem = Cart::where('user_id', $user->id)
            ->where('product_id', $validatedData['product_id'])
            ->first();

        if ($cartItem) {
            $cartItem->quantity += $validatedData['quantity'];

            $cartItem->save();

            return response()->json(['message' => 'Cart updated successfully.', 'cart_item' => $cartItem], 200);
        } else {
            $cartItem = Cart::create([
                'user_id' => $user->id,
                'product_id' => $validatedData['product_id'],
                'quantity' => $validatedData['quantity'],
            ]);

            return response()->json(['message' => 'Item added to cart successfully.', 'cart_item' => $cartItem], 201);
        }
    }

    public function remove(Request $request)
    {
        $validatedData = $request->validate([
            'product_id' => 'required|integer|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $user = $request->user();

        $cartItem = Cart::where('user_id', $user->id)
            ->where('product_id', $validatedData['product_id'])
            ->first();

        if ($cartItem) {
            $cartItem->quantity -= $validatedData['quantity'];

            if ($cartItem->quantity <= 0) {
                $cartItem->delete();
                return response()->json(['message' => 'Item removed from cart successfully.'], 200);
            } else {
                $cartItem->save();
                return response()->json(['message' => 'Cart updated successfully.', 'cart_item' => $cartItem], 200);
            }
        } else {
            return response()->json(['message' => 'Cart item not found.'], 404);
        }
    }
}
