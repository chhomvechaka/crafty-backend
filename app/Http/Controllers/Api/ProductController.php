<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    /**
     * Store a newly created product in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'product_name' => 'required|string|max:255',
            'product_description' => 'required|string|max:1000',
            'base_price' => 'required|integer', // Correct field name
            'stock' => 'required|integer', // Correct validation rule
            'tag_id' => 'nullable|integer',
//            'product_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        try {
            // Check if the user is a seller
            $user = Auth::user();
            if (!$user->isSeller()) {

                return response()->json(['message' => 'Only sellers can create products.'], 403);
            }

            // Check if the seller has a store
            $store = Store::where('user_id', Auth::id())->first();
            if (!$store) {

                return response()->json(['message' => 'You must create a store before adding products.'], 400);
            }

            // Create the product
            $product = Product::create([
                'product_name' => $request->product_name,
                'product_description' => $request->product_description,
                'base_price' => $request->base_price,
                'stock' => $request->stock,
                'tag_id' => $request->tag_id,
                'store_id' => $store->store_id,
            ]);

            // Handle the product image uploads
//            if ($request->hasFile('product_images')) {
//                foreach ($request->file('product_images') as $image) {
//                    $product->addMedia($image)->toMediaCollection('product_images');
//                }
//            }

            return response()->json(['product' => $product], 201);
        } catch (\Exception $e) {
            Log::error('Error creating product: ' . $e->getMessage());
            return response()->json(['message' => 'Server error', 'error' => $e->getMessage()], 500);
        }
    }
}
