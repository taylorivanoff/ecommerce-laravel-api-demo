<?php

namespace App\Http\Controllers;

use App\Http\Resources\SupplierOrderResource;
use Illuminate\Http\Request;

class SupplierOrderController extends Controller
{
    public function __invoke(Request $request)
    {
        $user = $request->user();

        $orders = $user->products()
            ->with('orders.user')
            ->with([
                'orders.items' => function ($query) use ($user) {
                    $query->where('supplier_id', $user->id);
                }
            ])
            ->orderByDesc('id')
            ->get();

        $orders->each(function ($product) use ($user) {
            $product->orders->each(function ($order) use ($user) {
                $order->supplier_amount = $order->items
                    ->sum(function ($item) {
                        return $item->pivot->quantity * $item->pivot->price;
                    });
            });
        });

        return SupplierOrderResource::collection($orders);
    }
}
