<?php

namespace App\Http\Controllers\APIs;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\onboard_connections;
use Illuminate\Support\Facades\Config;

class Invoicedata extends Controller
{
    public function getdata(Request $request){
        $dist = $request->input('dist');
        $distid = $request->input('distid');
        $tableName = $request->input('table_name');
        if($dist!='' && $distid!=''){
        $invoice=DB::connection('dynamic')
            ->table($tableName)
            ->where('sobi_flag', '0')
            ->where($tableName.'.party', $dist)->where($tableName.'.partycode', $distid)
            ->select(
                DB::raw('MAX(oc_cobi.date) as date'),'party','invoice','totalquantity','grandtotal',
                DB::raw('SUM('.$tableName.'.taxamount) as ttltaxamt'),
                DB::raw('SUM('.$tableName.'.totalwithtax-'.$tableName.'.taxamount) as ttlamt'))->groupBy('invoice')->get();   
            $pono=DB::connection('dynamic')
                ->table($tableName)
                ->where($tableName.'.party', $dist)->where($tableName.'.partycode', $distid)
                ->join('oc_salesorders', $tableName.'.so', '=', 'oc_salesorders.po')->groupBy('invoice')
                ->where('sobi_flag', '0')->select('oc_salesorders.pono as ponum')->get(); 
        }else{
             $invoice=DB::connection('dynamic')
            ->table($tableName)
            ->where('sobi_flag', '0')
            ->select(
                DB::raw('MAX(oc_cobi.date) as date'),'party','invoice','totalquantity','grandtotal',
                DB::raw('SUM('.$tableName.'.taxamount) as ttltaxamt'),
                DB::raw('SUM('.$tableName.'.totalwithtax-'.$tableName.'.taxamount) as ttlamt'))->groupBy('invoice')->get();   
            $pono=DB::connection('dynamic')
                ->table($tableName)
                ->join('oc_salesorders', $tableName.'.so', '=', 'oc_salesorders.po')->groupBy('invoice')
                ->where('sobi_flag', '0')->select('oc_salesorders.pono as ponum')->get();
        }
            return response()->json(['invoice' => $invoice,'pono' => $pono], 200);
       }
       public function invdata_invoice(Request $request){
        $tableName = $request->input('table_name');
        $dist = $request->input('dist');
        $distid = $request->input('distid');
        $primarydb = $request->input('db');
        $prodata=[];
        Config::set('database.connections.mysql2.database', $primarydb);
        $prodata1=onboard_connections::where('profile_flag' , '1')->where('primary_db', $primarydb)->get();
        foreach($prodata1 as $prof){
            DB::purge('dynamic');
            Config::set('database.connections.dynamic.database', $prof->db_name);
            if($distid!='' && $dist!=''){$invoice_cnt=DB::connection('dynamic')->table("oc_cobi")->where('sobi_flag', '0')->where('party', $dist)->where('partycode', $distid)->count();}
            else{$invoice_cnt=DB::connection('dynamic')->table("oc_cobi")->where('sobi_flag', '0')->count();}

            if($invoice_cnt>0){
             $prodata[]=onboard_connections::where(['id' => $prof->id])->get();
            }
        }
            return response()->json(['profile' => $prodata], 200);
       }
       public function updateinvoicedata(Request $request){
        $tableName = $request->input('table_name');
        $invoice = $request->input('invoice');
           $invoice=DB::connection('dynamic')
           ->table($tableName)
           ->whereIn('invoice', $invoice)
           ->where('sobi_flag', '0')->update(['sobi_flag' => '1']); 
            return response()->json(['success' => 1], 200);
       }
       public function getinvoicedata(Request $request){
        $tableName = $request->input('table_name');
        $invoice = $request->input('invoice');
           $invoice_data=DB::connection('dynamic')
            ->table($tableName)
            ->whereIn('invoice', $invoice)
            ->where('sobi_flag', '0')->get();  
            return response()->json(['invoice' => $invoice_data], 200);
       }
       public function viewinvoice(Request $request){
        $tableName = $request->input('table_name');
        $invoice = $request->input('invoice');
        //    $invoice_data=DB::connection('dynamic')
        //     ->table($tableName)
        //     ->join('oc_salesorders', $tableName.'.so', '=', 'oc_salesorders.po')
        //     ->where('sobi_flag', '0')
        //     ->where('invoice', $invoice)
        //     ->select('oc_cobi.date as date','party','invoice','totalquantity','grandtotal','oc_salesorders.pono as ponum',$tableName.'.taxamount as ttltaxamt',
        //     DB::raw('('.$tableName.'.totalwithtax-'.$tableName.'.taxamount) as ttlamt'))->get();  
    $subquery = DB::table('oc_salesorders')
    ->select('pono')
    ->whereColumn('po', $tableName.'.so')
    ->limit(1);
$invoice_data = DB::connection('dynamic')->table($tableName)
    ->select('date','party','invoice','totalquantity','grandtotal','taxamount as ttltaxamt',
    DB::raw('(totalwithtax-taxamount) as ttlamt'))
    ->selectSub($subquery, 'ponum')
    ->where('sobi_flag', '0')
    ->where('invoice', $invoice)
    ->get();  
            return response()->json(['invoice' => $invoice_data], 200);
       }
}
