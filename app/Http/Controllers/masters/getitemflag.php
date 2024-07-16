<?php

namespace App\Http\Controllers\Masters;

use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Http;

class GetItemFlag extends Controller
{
    public function fetchItemDetails()
    {
        $apiUrl = 'https://secondary.sbl1972.in/secondarysales/getitemflag.php';

        try {
            $response = Http::get($apiUrl);

            if ($response->successful()) {
                return response($response->body(), $response->status());
            } else {
                return response()->json([
                    'error' => 'Failed to fetch item details',
                    'status' => $response->status()
                ], $response->status());
            }
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'An error occurred while fetching item details',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
