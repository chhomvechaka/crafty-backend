<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class StoreController extends Controller
{
    /**
     * Store a newly created store in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'store_name' => 'required|string|max:255',
            'store_description' => 'required|string|max:1000',
            'store_contact' => 'required|string|max:255',
            'store_address' => 'required|string|max:255',
            'store_logo_path' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        try {
            // Check if the user already has a store
            if (Store::where('user_id', Auth::id())->exists()) {
                return response()->json(['message' => 'You already have a store.'], 400);
            }

            // Create the store
            $store = Store::create([
                'store_name' => $request->store_name,
                'store_description' => $request->store_description,
                'store_contact' => $request->store_contact,
                'store_address' => $request->store_address,
                'store_logo_path' => "placeholder", // Temporary placeholder
                'user_id' => Auth::id(),
            ]);

            // Handle the store logo upload
            if ($request->hasFile('store_logo_path')) {
                $store->addMediaFromRequest('store_logo_path')->toMediaCollection('store_logo');
            } else {
                $store->addMedia(storage_path('app/public/default_store_logo.png'))->toMediaCollection('store_logo');
            }

            // Update the store with the correct logo path
            $store['store_logo_path'] = $store->getMedia('store_logo')->first()->getUrl();

            return response()->json(['store' => $store], 201);
        } catch (\Exception $e) {
            Log::error('Error creating store: ' . $e->getMessage());
            return response()->json(['message' => 'Server error', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Display a listing of the stores.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try {
            $stores = Store::all();
            // $stores = Store::where('user_id', Auth::id())->get();
            foreach ($stores as $store) {
                $store['store_logo_path'] = $store->getMedia('store_logo')->first() ? $store->getMedia('store_logo')->first()->getUrl() : null;
            }

            return response()->json(['stores' => $stores], 200);
        } catch (\Exception $e) {
            Log::error('Error fetching stores: ' . $e->getMessage());
            return response()->json(['message' => 'Server error', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Display a listing of the stores by user ID.
     *
     * @param  int  $userId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getStoresByUserId()
    {
        $userId = Auth::id(); // Get the current authenticated user's ID
        $store = Store::where('user_id', $userId)->first(); // Assuming each user has only one store

        if (!$store) {
            return response()->json(['message' => 'No store found'], 404);
        }

        $store['store_logo_path'] = $store->getMedia('store_logo')->first() ? $store->getMedia('store_logo')->first()->getUrl() : 'default_path';

        return response()->json(['store' => $store], 200);
    }



    public function getAllStore()
    {
        try {
            $stores = Store::all();
            foreach ($stores as $store) {
                $store['store_logo_path'] = $store->getMedia('store_logo')->first() ? $store->getMedia('store_logo')->first()->getUrl() : null;
            }

            return response()->json(['stores' => $stores], 200);
        } catch (\Exception $e) {
            Log::error('Error fetching all stores: ' . $e->getMessage());
            return response()->json(['message' => 'Server error', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified store from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $store = Store::find($id);

            if (!$store || $store->user_id !== Auth::id()) {
                Log::warning('Unauthorized store deletion attempt by user: ' . Auth::user()->email . ' for store_id: ' . $id);
                return response()->json(['message' => 'Store not found or unauthorized'], 404);
            }

            $store->delete();
            Log::info('Store deleted by user: ' . Auth::user()->email, ['store_id' => $id]);

            return response()->json(['message' => 'Store deleted successfully'], 200);
        } catch (\Exception $e) {
            Log::error('Error deleting store: ' . $e->getMessage());
            return response()->json(['message' => 'Server error', 'error' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        // Validate the incoming request
        $request->validate([
            'store_name' => 'sometimes|required|string|max:255',
            'store_description' => 'sometimes|required|string|max:1000',
            'store_contact' => 'sometimes|required|string|max:255',
            'store_address' => 'sometimes|required|string|max:255',
            'store_logo_path' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        try {
            // Find the store by ID and ensure it belongs to the authenticated user
            $store = Store::where('store_id', $id)->where('user_id', Auth::id())->first();

            if (!$store) {
                return response()->json(['message' => 'Store not found or unauthorized'], 404);
            }

            // Update store details
            $store->update($request->only(['store_name', 'store_description', 'store_contact', 'store_address']));

            // Handle the store logo upload
            if ($request->hasFile('store_logo_path')) {
                $store->clearMediaCollection('store_logo');
                $store->addMediaFromRequest('store_logo_path')->toMediaCollection('store_logo');
            }

            // Refresh the store instance to get the updated data
            $store->refresh();

            // Update the store with the correct logo path
            $store['store_logo_path'] = $store->getMedia('store_logo')->first() ? $store->getMedia('store_logo')->first()->getUrl() : null;

            return response()->json(['store' => $store], 200);
        } catch (\Exception $e) {
            Log::error('Error updating store: ' . $e->getMessage());
            return response()->json(['message' => 'Server error', 'error' => $e->getMessage()], 500);
        }
    }
}
