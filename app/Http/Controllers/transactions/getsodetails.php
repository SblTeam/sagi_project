<?php
namespace App\Http\Controllers\Transactions;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use App\Models\oc_salesorder;
class getsodetails extends Controller
{


    public function fetchsoDetails (Request $request)
    {

      $so = $request->input('so');



$results = oc_salesorder::where('po', $so)
    ->distinct()
    ->select('category', 'code', 'description', 'squantity', 'unit', 'rateperunit', 'taxcode', 'taxvalue', 'sprice')
    ->get();
    $details = [];

    foreach ($results as $row) {
        $details[] = $row->category . "@" . $row->code . "@" . $row->description . "@" . $row->squantity . "@" . $row->unit . "@" . $row->sprice . "@" . $row->taxcode . "@" . $row->taxvalue;
    }

return json_encode($details);
    }



}
