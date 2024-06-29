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
                'product_id' => $request->product_id,
                'user_id' => Auth::id(),
            ],
            [
                'design_element' => json_decode($request->design_element, true),
            ]
        );

        return response()->json(['message' => 'Product option saved successfully!', 'productOption' => $productOption], 200);
    }


    public function getSavedDesigns(Request $request)
    {
        $userId = Auth::id();
        $savedDesigns = ProductOption::where('user_id', $userId)->get();

        return response()->json(['savedDesigns' => $savedDesigns], 200);
    }

    public function index()
    {
        //
    }

    public function show(ProductOption $productOption)
    {
        //
    }

    public function destroy(ProductOption $productOption)
    {
        //
    }
}
