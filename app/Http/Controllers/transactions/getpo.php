<?php

namespace App\Http\Controllers\Transactions;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class GetPo extends Controller
{
    public function fetchPoDetails(Request $request)
    {
        $poNumber = $request->input('po');
        $apiUrl = 'https://secondary.sbl1972.in/secondarysales/getdeatilsapi.php';

        // Initialize cURL session
        $ch = curl_init();

        // Set the URL with query parameters
        curl_setopt($ch, CURLOPT_URL, $apiUrl . '?' . http_build_query(['po' => $poNumber]));

        // Set options to return the response as a string
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Optionally, disable SSL verification (not recommended for production)
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        // Execute cURL request
        $response = curl_exec($ch);

        // Check for errors
        if (curl_errno($ch)) {
            $error_msg = curl_error($ch);
            // Handle error as needed
            curl_close($ch);
            return response()->json(['error' => 'cURL error: ' . $error_msg], 500);
        }

        // Close cURL session
        curl_close($ch);

        // Return response
        return response($response, 200)
                  ->header('Content-Type', 'application/json');
    }
}
