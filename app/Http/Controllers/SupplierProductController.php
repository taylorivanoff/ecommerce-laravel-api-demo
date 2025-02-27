<?php

namespace App\Http\Controllers;

use App\Http\Resources\SupplierProductResource;
use Illuminate\Http\Request;

class SupplierProductController extends Controller
{
    public function __invoke(Request $request)
    {
        $products = $request->user()->products()->get();

        return SupplierProductResource::collection($products);
    }
}
