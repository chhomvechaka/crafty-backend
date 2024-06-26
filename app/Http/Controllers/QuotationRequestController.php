<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\ProductOption;
use App\Models\QuotationRequest;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class QuotationRequestController extends Controller
{
    public function requestDesign(Request $request)
    {
        // Validate the request data
        $validated = $request->validate([
            'product_option_id' => 'required|exists:table_product_option,product_option_id',
        ]);

        $productOption = ProductOption::findOrFail($validated['product_option_id']);

        // Check if is_requested is already true
        if ($productOption->is_requested) {
            // Return a response or throw an exception indicating the request cannot be sent
            return response()->json(['message' => 'Request has already been sent for this product option.'], 422);
        }

        // Set is_requested to true
        $productOption->is_requested = true;
        $productOption->save();

        // Create the QuotationRequest
        $quotationRequest = QuotationRequest::create([
            'notes' => 'blah', // todo: to be deleted
            'user_id' => Auth::id(), // Assuming the user is authenticated
            'product_option_id' => $validated['product_option_id'],
        ]);

        // // Find the ProductOption by ID
        // $productOption = ProductOption::findOrFail($validated['product_option_id']);

        // // Set is_requested to true
        // $productOption->is_requested = true;
        // $productOption->save();

        // Return a response
        return response()->json(['message' => 'Design request submitted successfully', 'quotationRequest' => $quotationRequest], 201);
    }

    public function getSellerRequests(Request $request)
    {
        // Get the authenticated user
        $user = Auth::user();

        // Fetch all quotation requests where the store's user_id matches the authenticated user's user_id
        $requests = DB::table('table_quotation_request as qr')
            ->join('table_product_option as po', 'qr.product_option_id', '=', 'po.product_option_id')
            ->join('table_product as p', 'po.product_id', '=', 'p.product_id')
            ->join('table_stores as s', 'p.store_id', '=', 's.store_id')
            ->where('s.user_id', $user->user_id)
            ->select('qr.*', 'po.*', 'p.*', 's.*')
            ->get();

        return response()->json($requests);
    }
}
