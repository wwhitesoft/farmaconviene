<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Webkul\Product\Models\ProductFlat;

class SearchController extends Controller
{
    public function search(Request $request)
{
    $products = ProductFlat::search($request->q)->get();

    \Log::info('Search Results:', ['products' => $products]);

    return response()->json([
        'products' => $products->map(function($product) {
            $baseImageUrl = $product->getBaseImageUrl();
            \Log::info('Product Image URL:', ['id' => $product->id, 'url' => $baseImageUrl]);

            return [
                'id' => $product->id,
                'name' => $product->name,
                'base_image' => $baseImageUrl,
                'formatted_price' => core()->currency($product->price),
                'formatted_price_discounted' => core()->currency($product->special_price),
                'product_url' => $product->url_key
            ];
        })
    ]);
}

}