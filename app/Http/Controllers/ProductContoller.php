<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Http;
use App\Jobs\CopyProductImage;

class ProductContoller extends Controller
{
    public function index() {
        $products = Product::paginate(10);

        return view('products.index', compact('products'));
    }

    public function syncProducts()
    {
        // get first 15 products
        $response = Http::withBasicAuth(
                config('services.wooapi.consumer_key'),
                config('services.wooapi.consumer_secret')
            )->get(
                config('services.wooapi.base_url') . 'wp-json/wc/v3/products',
                [
                    'wp_api' => true,
                    'version' => 'wc/v3',
                    'per_page' => 15,
                    'order' => 'asc'
                ]
            );

        $wooProducts = $response->object();

        foreach($wooProducts as $wooProduct) {

            // check record exists if exists update record
            if(Product::where('product_id', '=', $wooProduct->id)->exists()) {
                $product = Product::where('product_id', '=', $wooProduct->id)->first();
            } else {
                $product = new Product();
            }
            
            $product->product_id = $wooProduct->id;
            $product->name = $wooProduct->name;
            $product->price = $wooProduct->price;
            $product->description = $wooProduct->description;
            $product->save();

            // call product image sync queue job
            CopyProductImage::dispatch($product->id, $wooProduct->images[0]->src);
        }
    
        return redirect('/');
    }
}
