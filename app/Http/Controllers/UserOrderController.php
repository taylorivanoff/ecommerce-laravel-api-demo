<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserOrderResource;
use App\Models\Order;
use Illuminate\Http\Request;

class UserOrderController extends Controller
{
    public function __invoke(Request $request)
    {
        $orders = $request->user()
            ->orders()
            ->orderByDesc('id')
            ->get();

        return UserOrderResource::collection($orders);
    }
}
