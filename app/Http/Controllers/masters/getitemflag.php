<?php

namespace App\Http\Controllers\Masters;

use App\Http\Controllers\Controller;

class GetItemFlag extends Controller
{
    public function fetchItemDetails()
    {
        $apiUrl = 'https://secondary.sbl1972.in/secondarysales/getitemflag.php';

        try {
            $ch = curl_init();

            // Set the cURL options
            curl_setopt($ch, CURLOPT_URL, $apiUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Disable SSL verification

            // Execute the request
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

            // Check for cURL errors
            if (curl_errno($ch)) {
                throw new \Exception(curl_error($ch));
            }

            curl_close($ch);

            // Check if the request was successful
            if ($httpCode === 200) {
                return response($response, $httpCode);
            } else {
                return response()->json([
                    'error' => 'Failed to fetch item details',
                    'status' => $httpCode
                ], $httpCode);
            }
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'An error occurred while fetching item details',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
