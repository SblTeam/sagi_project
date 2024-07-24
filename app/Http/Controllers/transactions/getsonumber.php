<?php
namespace App\Http\Controllers\Transactions;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use App\Models\oc_salesorder;
class getsonumber extends Controller
{


    public function fetchsoDetails (Request $request)
    {

      $id = $request->input('id');

      $distinctPos = oc_salesorder::where('vendorid', $id)
                           ->distinct()
                           ->pluck('po');

    $distinctPosArray = $distinctPos->toArray();

    $so = json_encode($distinctPosArray);
    return $so;


    }



}
