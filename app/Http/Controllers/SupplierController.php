<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SupplierController extends Controller
{
    public function products(Request $request)
    {
        $products = $request->user()->products()->get();

        return response()->json($products, 200);
    }

    public function orders(Request $request)
    {
        $user = $request->user();

        $products = $user->products()
            ->with('orders.user')
            ->with([
                'orders.items' => function ($query) use ($user) {
                    $query->where('supplier_id', $user->id);
                }
            ])
            ->orderByDesc('id')
            ->get();

        // Calculate supplier_amount for each order
        $products->each(function ($product) use ($user) {
            $product->orders->each(function ($order) use ($user) {
                $order->supplier_amount = $order->items
                    ->sum(function ($item) {
                        return $item->pivot->quantity * $item->pivot->price;
                    });
            });
        });


        return response()->json($products, 200);
    }
}
