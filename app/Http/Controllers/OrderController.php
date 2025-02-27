<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index()
    {
        return Order::with('items')->where('user_id', Auth::id())->orderByDesc('id')->get();
    }

    public function checkout(Request $request)
    {
        $validatedData = $request->validate([
            'payment_method' => 'required|string',
            'address' => 'required|string|max:255',
        ]);

        $user = Auth::user();

        DB::beginTransaction();

        try {
            $cartItems = Cart::where('user_id', $user->id)->get();

            if ($cartItems->isEmpty()) {
                return response()->json(['message' => 'Cart is empty.'], 400);
            }

            $order = Order::create([
                'user_id' => $user->id,
                'total_amount' => $cartItems->sum(function ($item) {
                    return $item->quantity * $item->product->price;
                }),
                'payment_method' => $validatedData['payment_method'],
                'address' => $validatedData['address'],
                'status' => 'pending',
            ]);

            foreach ($cartItems as $cartItem) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cartItem->product_id,
                    'quantity' => $cartItem->quantity,
                    'price' => $cartItem->product->price,
                ]);
            }

            $paymentSuccessful = true;

            if ($paymentSuccessful) {
                $order->status = 'completed';
                $order->save();

                Cart::where('user_id', $user->id)->delete();

                DB::commit();

                return response()->json(['message' => 'Checkout successful.', 'order' => $order], 201);
            } else {
                throw new \Exception('Payment failed.');
            }
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => 'Checkout failed: ' . $e->getMessage()], 500);
        }
    }
}
