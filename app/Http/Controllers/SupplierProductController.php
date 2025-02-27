<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SupplierProductController extends Controller
{
    public function __invoke(Request $request)
    {
        $products = $request->user()->products()->get();

        return response()->json($products, 200);
    }
}
