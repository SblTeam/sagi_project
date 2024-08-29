<?php
namespace App\Http\Controllers\Transactions;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class getpo extends Controller
{
    public function fetchPoDetails(Request $request)
    {
        $poNumber = $request->input('po');

        $apiData = [
     
            'po' => $poNumber,
            'db' => session()->get("db"),

        
    ];



        $apiData = json_encode($apiData);


        $ch = curl_init();
    
        // Set cURL options
        curl_setopt($ch, CURLOPT_URL, 'https://secondary.sbl1972.in/secondarysales/getdeatilsapi.php');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $apiData); // Encode data in JSON format
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        // Execute cURL request
        $response = curl_exec($ch);

        // Get HTTP status code
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        // Get cURL error if any
        $curlError = curl_error($ch);

        // Close cURL session
        curl_close($ch);

        if ($httpCode == 200 && !$curlError) {
            // Return the response body with status code
            return response($response, $httpCode);
        } else {
            // Handle failed response
            return response()->json([
                'error' => 'Failed to fetch PO details from the server: ' . $curlError
            ], $httpCode);
        }
    }
}
