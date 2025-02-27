<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class UserOrderController extends Controller
{
    public function __invoke(Request $request)
    {
        return $request->user()->orders()->orderByDesc('id')->get();
    }
}
