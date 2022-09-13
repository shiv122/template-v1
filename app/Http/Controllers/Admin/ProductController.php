<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    public function index()
    {
        return view('content.tables.product-table');
    }

    public function edit(Request $request)
    {
        Log::info($request->all());
        return $product = Product::find($request->id);
    }

    public function update(Request $request)
    {
        return $request;
    }
}
