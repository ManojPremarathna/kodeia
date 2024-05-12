<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all();

        foreach($products as $product) {
            $data[] = [
                'id' => $product->id,
                'name' => $product->name,
                'image' => $product->productImage(),
                'description' => $product->description
            ];
        }

        return response([
            'status'  => true,
            'message' => 'Successful',
            'data'    => $data
        ], 200);
    }
}
