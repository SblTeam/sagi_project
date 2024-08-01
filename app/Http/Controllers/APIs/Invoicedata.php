<?php

namespace App\Http\Controllers\APIs;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class Invoicedata extends Controller
{
    public function getdata(Request $request){
    $tableName = $request->input('table_name');
       $invoice=DB::connection('dynamic')
        ->table($tableName)
        ->join('oc_salesorders', $tableName.'.so', '=', 'oc_salesorders.po')->groupBy('invoice')
        ->where('sobi_flag', '0')
        ->select(
            DB::raw('MAX(oc_cobi.date) as date'),'party','invoice','totalquantity','grandtotal','oc_salesorders.pono as ponum',
            DB::raw('SUM('.$tableName.'.taxamount) as ttltaxamt'),
            DB::raw('SUM('.$tableName.'.totalwithtax-'.$tableName.'.taxamount) as ttlamt'))->get();   
        return response()->json(['invoice' => $invoice], 200);
   }
}
