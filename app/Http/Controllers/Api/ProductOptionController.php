<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ProductOption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductOptionController extends Controller
{

    public function saveDesign(Request $request)
    {
        $request->validate([
            'design_element' => 'required|json',
            'product_id' => 'required|exists:table_product,product_id',
        ]);

        $productOption = ProductOption::updateOrCreate(
            [
                // Criteria to find the existing record
                'product_id' => $request->product_id,
                'user_id' => Auth::id(),
            ],
            [
                // Data to update or create
                'design_element' => json_decode($request->design_element, true),
            ]
        );

        $productOption->save();

        return response()->json(['message' => 'Product option saved successfully!', 'productOption' => $productOption], 200);
    }

    public function getSavedDesigns(Request $request)
    {
        // Assuming 'user_id' is automatically determined from the authenticated user
        $userId = Auth::id();

        // Retrieve all product options for the authenticated user
        $savedDesigns = ProductOption::where('user_id', $userId)->get();

        // Return the designs as a JSON response
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
