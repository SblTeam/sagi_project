<?php
namespace App\Http\Controllers\Transactions;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class getpo extends Controller
{


    public function fetchPoDetails (Request $request)
    {


        $poNumber = $request->input('po');

        $apiUrl = 'https://secondary.sbl1972.in/secondarysales/getdeatilsapi.php';


        $response = Http::get($apiUrl, ['po' => $poNumber]);


        return response($response->body(), $response->status());
    }



}
