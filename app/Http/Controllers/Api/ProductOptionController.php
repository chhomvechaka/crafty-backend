<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ProductOption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Illuminate\Support\Facades\Log;

class ProductOptionController extends Controller
{
    public function saveDesign(Request $request)
    {
        $request->validate([
            'design_element' => 'required|json',
            'product_id' => 'required|exists:table_product,product_id',
        ]);

        $designElement = json_decode($request->design_element, true);

        $productOption = ProductOption::updateOrCreate(
            [
                'product_id' => $request->product_id,
                'user_id' => Auth::id(),
            ],
            [
                'design_element' => $designElement,
            ]
        );

        $storedMediaUrls = [];

        // Handle base64 images
        if (isset($designElement['images'])) {
            foreach ($designElement['images'] as $index => $image) {
                if (strpos($image['src'], 'data:image') === 0) {
                    // Decode base64 string
                    $imageData = $this->decodeBase64Image($image['src']);
                    if ($imageData) {
                        // Save the decoded image to a temporary file
                        $tempFilePath = 'public/tmp/' . Str::random(40) . '.' . $imageData['extension'];
                        Storage::put($tempFilePath, $imageData['data']);
                        
                        Log::info('Temporary file created', ['path' => $tempFilePath]);

                        // Add the image to the media library
                        $media = $productOption->addMedia(storage_path('app/' . $tempFilePath))->toMediaCollection('design_images');

                        // Check if the media was added successfully and store the URL
                        if ($media) {
                            $storedMediaUrls[] = $media->getUrl();
                            Log::info('Image stored successfully', ['media' => $media]);
                        } else {
                            Log::error('Failed to store image');
                        }

                        // Delete the temporary file
                        Storage::delete($tempFilePath);
                        Log::info('Temporary file deleted', ['path' => $tempFilePath]);
                    }
                }
            }
        }

        $productOption->save();

        return response()->json([
            'message' => 'Product option saved successfully!',
            'productOption' => $productOption,
            'imageUrls' => $storedMediaUrls
        ], 200);
    }

    protected function decodeBase64Image($base64Image)
    {
        // Check if base64 string is valid
        if (preg_match('/^data:image\/(\w+);base64,/', $base64Image, $type)) {
            // Get the image extension
            $imageType = strtolower($type[1]);

            // Decode the base64 string
            $imageData = base64_decode(substr($base64Image, strpos($base64Image, ',') + 1));

            if ($imageData) {
                return [
                    'extension' => $imageType,
                    'data' => $imageData,
                ];
            }
        }

        return null;
    }

    public function getSavedDesigns(Request $request)
{
    $userId = Auth::id();
    $savedDesigns = ProductOption::where('user_id', $userId)->get()->map(function ($productOption) {
        $designElement = $productOption->design_element;
        $designElement['imageUrls'] = $productOption->getMedia('design_images')->map(function (Media $media) {
            return $media->getUrl();
        })->toArray();

        return [
            'productOption' => $productOption->only(['product_option_id', 'product_id', 'created_at', 'updated_at']),
            'designElement' => $designElement,
        ];
    });

    return response()->json(['savedDesigns' => $savedDesigns], 200);
}

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ProductOption  $productOption
     * @return \Illuminate\Http\Response
     */
    public function show(ProductOption $productOption)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ProductOption  $productOption
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProductOption $productOption)
    {
        //
    }
}
