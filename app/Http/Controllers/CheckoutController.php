<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\CheckoutRequest;
use App\Http\Resources\UserOrderResource;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CheckoutController extends Controller
{
    public function __invoke(CheckoutRequest $request)
    {
        $validated = $request->validated();

        $user = $request->user();

        DB::beginTransaction();

        try {
            $cart = Cart::where('user_id', $user->id)->get();

            if ($cart->isEmpty()) {
                throw new ModelNotFoundException('Cart is empty.');
            }

            $order = Order::create([
                'user_id' => $user->id,
                'total_amount' => $cart->sum(function ($item) {
                    return $item->quantity * $item->product->price;
                }),
                'payment_method' => $validated['payment_method'],
                'address' => $validated['address'],
                'status' => 'pending',
            ]);

            foreach ($cart as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->product->price,
                ]);
            }

            $isPaymentSuccessful = true; // Simulated

            if (!$isPaymentSuccessful) {
                throw new \Exception('Payment failed.');
            }

            $order->status = 'completed';
            $order->save();

            Cart::where('user_id', $user->id)->delete();

            DB::commit();

            return new UserOrderResource($order);
        } catch (\Exception $e) {
            DB::rollback();

            throw new \Exception('Checkout failed.');
        }
    }
}
