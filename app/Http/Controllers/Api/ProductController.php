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
    public function storeProductsToStore(Store $store, Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'product_name' => 'required|string|max:255',
            'product_description' => 'required|string|max:1000',
            'base_price' => 'required|integer', // Correct field name
            'stock' => 'required|integer', // Correct validation rule
            'tag_id' => 'nullable|integer',
            'product_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        try {
            // Check if the store exists and if the authenticated user is the owner
            if ($store->user_id !== Auth::id()) {
                return response()->json(['message' => 'Unauthorized to add products to this store. You are not the owner.'], 403);
            }

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
                'tag_id' => 1,
                'store_id' => $store->store_id,
            ]);

            if ($request->hasFile('product_images')) {
                foreach ($request->file('product_images') as $image) {
                    $product->addMedia($image)->toMediaCollection('product_images');
                }
            }

            $productImages = $product->getMedia('product_images')->map(function ($mediaItem) {
                return $mediaItem->getUrl();
            })->toArray();

            return response()->json([
                'product' => $product,
                'images' => $productImages,
            ], 201);
        } catch (\Exception $e) {
            Log::error('Error creating product: ' . $e->getMessage());
            return response()->json(['message' => 'Server error', 'error' => $e->getMessage()], 500);
        }
    }

    public function getProductsByStore(Store $store)
    {
        try {
            // Fetch all products of the store
            $products = $store->products()->get()->map(function ($product) {
                // For each product, fetch its images
                $images = $product->getMedia('product_images')->map(function ($media) {
                    // Return both the media item's ID and URL
                    return [
                        'id' => $media->id, // Include the image ID
                        'url' => $media->getUrl(), // Get the URL of each image
                    ];
                })->toArray();

                // Return the product details along with its images
                return [
                    'product_id' => $product->product_id,
                    'product_name' => $product->product_name,
                    'product_description' => $product->product_description,
                    'base_price' => $product->base_price,
                    'stock' => $product->stock,
                    'images' => $images, // Include images in the product details
                ];
            });

            // Return the response with products and their images
            return response()->json(['products' => $products], 200);
        } catch (\Exception $e) {
            Log::error('Error fetching products by store: ' . $e->getMessage());
            return response()->json(['message' => 'Server error', 'error' => $e->getMessage()], 500);
        }
    }
}
