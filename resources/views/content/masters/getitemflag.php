<?php

namespace App\Http\Controllers\Masters;

use App\Http\Controllers\Controller;

class GetItemFlag extends Controller
{
    public function fetchItemDetails()
    {
        $apiUrl = 'https://secondary.sbl1972.in/secondarysales/getitemflag.php';

        $ch = curl_init($apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
       
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json'
        ]);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        // Execute cURL request
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);

           if ($httpCode === 200) {
                return response($response, $httpCode);
            } else {
                return response()->json([
                    'error' => 'Failed to fetch item details',
                    'status' => $httpCode
                ], $httpCode);
            }

    }
}
