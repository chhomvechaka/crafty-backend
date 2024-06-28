<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Store;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class StoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $defaultLogos = [
        //     'public/assets/default_store_logo1.png',
        //     'public/assets/default_store_logo2.png',
        //     'public/assets/default_store_logo3.png',
        // ];
        $defaultLogos = [
            'assets/default_store_logo1.png',
            'assets/default_store_logo2.png',
            'assets/default_store_logo3.png',
        ];

        $buyers = User::whereHas('role', function ($query) {
            $query->where('role_name', 'seller');
        })->limit(3)->get();
        $i = 0;
        // Loop through each selected buyer and seed a store for them
        foreach ($buyers as $buyer) {
            // Create a new store and associate it with the buyer
            $store = Store::create([
                'user_id' => $buyer->user_id,
                'store_name' => 'Store for ' . $buyer->lastname,
                'store_description' => 'A store belonging to ' . $buyer->lastname,
                'store_contact' => 'Contact Information of ' . $buyer->lastname,
                'store_address' => 'Store Address of ' . $buyer->lastname,
                'store_logo_path' => 'path/to/logo.jpg',
            ]);

            if ($i >= count($defaultLogos)) {
                $i = 0;
            }

            // Use copy() method to temporarily copy the file to a local disk if not already there
            $temporaryLogoPath = Storage::disk('local')->path('temp_store_logos/' . basename($defaultLogos[$i]));
            if (!Storage::disk('local')->exists('temp_store_logos/' . basename($defaultLogos[$i]))) {
                Storage::disk('local')->copy($defaultLogos[$i], 'temp_store_logos/' . basename($defaultLogos[$i]));
            }

            // Add the logo to the store's media collection from the temporary path
            $store->addMedia($temporaryLogoPath)->toMediaCollection('store_logo');

            // Optionally, update the store with the correct logo path from the media item
            $mediaItem = $store->getFirstMedia('store_logo');
            if ($mediaItem instanceof Media) {
                $store->store_logo_path = $mediaItem->getUrl();
                $store->save();
            }

            $i++;
        }
    }
}
