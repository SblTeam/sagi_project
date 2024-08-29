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
    ->select('category', 'code', 'description', 'squantity', 'sprice', 'taxableprice', 'basic','taxcode', 'taxvalue', 'totalwithtax','taxamount')
    ->where ('cobi_flag' , 0)
    ->get();
    $details = [];

    foreach ($results as $row) {
        $details[] = $row->category . "@" . $row->code . "@" . $row->description . "@" . $row->squantity . "@" . $row->sprice . "@" . $row->taxableprice . "@" . $row->basic . "@" . $row->taxcode . "@" . $row->taxvalue. "@" . $row->taxamount. "@" . $row->totalwithtax;
    }

return json_encode($details);
    }



}
