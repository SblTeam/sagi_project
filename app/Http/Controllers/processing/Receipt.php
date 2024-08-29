<?php

namespace App\Http\Controllers\processing;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\oc_receipt;
use App\Models\oc_cobi;
use App\Models\ac_financialpostings;
use Illuminate\Support\Facades\DB;
use App\Models\contactdetails;
use Exception;

class Receipt extends Controller
{
  public function index()
  {
    $receipt = oc_receipt::all();
    return view('content.processing.receipt', compact('receipt'));
  }
  public function add()
  {
    $oc_cobi = oc_cobi::groupBy("party")->get();
    return view('content.processing.addreceipt', compact('oc_cobi'));
  }
  public function getinvoicewithparty(Request $request)
  {
    $oc_cobi1 = oc_cobi::where("party",$request->party)->groupby('invoice')->get();$penbal=[];
    foreach($oc_cobi1 as $invoice){
      $receipt1 =oc_receipt::select(DB::raw('SUM(amountreceived) as amt'))
      ->where('party', $request->party)
      ->where('socobi', $invoice->invoice)
      ->first();
      $penbal[$invoice->invoice]=$invoice->grandtotal-$receipt1->amt;
    }
    return response()->json(["data" => $oc_cobi1,"penbal" => $penbal],200);
  }

  public function getinvoicewithparty_check(Request $request)
  {
    $invoice = oc_cobi::where("party",$request->party)->where("invoice",$request->invoice)->first();
      $receipt1 =oc_receipt::select(DB::raw('SUM(amountreceived) as amt'))
      ->where('party', $request->party)
      ->where('socobi', $request->invoice)
      ->first();
      $penbal=$invoice->grandtotal-$receipt1->amt;
      if($penbal>=$request->amt){
        return response()->json(["data" => 1,"invoice" => $request->invoice],200);
      }else{
        return response()->json(["data" => 0,"invoice" => $request->invoice],200);
      }
  }
  public function store(Request $request)
  {
    $request->validate([
      'party' => 'required',
      'partyid' => 'required',
      'Docno' => "required|max:100|regex:/^[A-Za-z0-9\s.\-_]+$/",
      'paymentmethod' => "required",
      'choice' => 'required',
      'paymentmode' => 'required',
  ],[
    'name.Docno' =>'Only alphabets (A-Z), numbers (0-9), special characters (.-_& ) are allowed',
  ]);
$tid =oc_receipt::select(DB::raw('max(tid) as tid'))->first();  
$trid=$tid->tid+1; 
DB::beginTransaction();
try{
  if ($request->paymentmethod == "Receipt" && $request->choice == "COBIs") {
    $tempamount = 0;$i=0;$kt=0;
foreach($request->amountreceived as $revamt){
  if($revamt!='' && $revamt>0){$kt++;    
    $invoice = oc_cobi::where("party",$request->party)->where("invoice",$request->cobi[$i])->first();
      $receipt1 =oc_receipt::select(DB::raw('SUM(amountreceived) as amt'))
      ->where('party', $request->party)->where('socobi', $request->cobi[$i])->first();
      $penbal=$invoice->grandtotal-$receipt1->amt;
      if($penbal>=$request->amountreceived[$i]){}else{
    return redirect()->route('processing-receipt.add')->with("Fail","Amount exceed on ".$request->cobi[$i]." in cobi");}
  $balc=$request->penbal[$i]-$request->amountreceived[$i];
  if($request->paymentmode=='Cheque'){$request->upi='';}
  else if($request->paymentmode=='Transfer'){$request->cheque='';}
  else{$request->upi='';$request->cheque='';}
  if($request->cheque==null || $request->cheque==''){$request->checkdate=NULL;}
  $contactDetail = ContactDetails::first();

  oc_receipt::create([
    'remarks' => $request->narration,'tid' => $trid, 'dr' => 'Dr', 'totalamount' => $request->recamt, 'date' => $request->date,'party' => $request->party,'partycode' => $request->partyid,'paymentmethod' => $request->paymentmethod,'paymentmode' => $request->paymentmode,'cdate' => $request->checkdate,'cheque' => $request->cheque,'upi' => $request->upi,'choice' => $request->choice,'socobi' => $request->cobi[$i],'actualamount' => $request->actualamt[$i],'amountreceived' => $request->amountreceived[$i],'balance' => $balc,'doc_no' => $request->Docno,'empname' => session()->get("valid_user"),'updated_at' => now(),'client' => $contactDetail->company,'created_at' => now()
]);
$cocode=ac_financialpostings::where([['venname', '=', $request->party],['crdr', '=', 'Dr'],['itemcode', '=', '']])->select('coacode')->first();
if($cocode->coacode==""){throw new Exception('Posting not happending on this party in cobi');}
ac_financialpostings::create([
  'date' => $request->date,'itemcode' => '','crdr' => 'Dr','coacode' => 'LI111','quantity' => 0,'amount' => $request->recamt,'trnum' => $trid,'type' => 'RCT','venname' => $request->party,'venid' => $request->partyid
]);
ac_financialpostings::create([
  'date' => $request->date,'itemcode' => '','crdr' => 'Cr','coacode' => $cocode->coacode,'quantity' => 0,'amount' => $request->recamt,'trnum' => $trid,'type' => 'RCT','venname' => $request->party,'venid' => $request->partyid
]);
oc_receipt::where('tid', $trid)->update(['flag' => 1,'updated_at' => now(),'aempname' => session()->get('valid_user')]);
}
  $i++;
}
if($kt==0){
  return redirect()->route('processing-receipt.add')->with("Fail","Total payable amount should greater than 0.");
}}DB::commit();
return redirect()->route('processing-receipt')->with('Success', 'Reciept Amount Saved Successfully.');
} catch (\Exception $e) {
  DB::rollBack();
  return redirect()->route('processing-receipt')->with('Fail', 'Error :'.throw $e);
}}

public function destroy(Request $request)
{
    $trnum=$request->id;
  DB::beginTransaction();
try{
     oc_receipt::where('tid',$trnum)->delete();
    ac_financialpostings::where('trnum',$trnum)->delete();
    DB::commit();
    return redirect()->route('processing-receipt')->with('Success', 'Reciept Deleted Successfully.');
}catch (\Exception $e) {
  DB::rollBack();
  return redirect()->route('processing-receipt')->with('Fail', 'Error :'.throw $e);
}}
  public function view(Request $request)
  {
    $receipt = oc_receipt::where('tid',$request->id)->first();
    $receiptall = oc_receipt::select('socobi', 'actualamount', 'amountreceived', 'amountreceived', 'oc_cobi.date')
    ->leftJoin('oc_cobi', 'socobi', '=', 'invoice')->where('tid',$request->id)->get();
    $oc_cobi = oc_cobi::groupBy("party")->get();
    return view('content.processing.viewreceipt', compact('receiptall','receipt','oc_cobi'));
  }
  
}
