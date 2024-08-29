<?php

namespace App\Http\Controllers\Transactions;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\oc_salesorder;
use App\Models\oc_cobi;
use App\Models\tcs_tax_percentage;
use App\Models\contactdetails;
use App\Models\ims_itemcodes;
use App\Models\oc_receipt;
use App\Models\ims_taxcodes;
use App\Models\ac_financialpostings;
use Illuminate\Support\Facades\Http;


class Invoice extends Controller
{


  public function index()
  {


    $socobiInvoices = DB::table('oc_receipt')->pluck('socobi')->toArray();
    
    $oc_cobi = oc_cobi::select('oc_cobi.*')
    ->join(DB::raw('(SELECT MIN(id) as id FROM oc_cobi GROUP BY invoice) as grouped'), 'oc_cobi.id', '=', 'grouped.id')
    ->get();
      return view('content.transactions.Invoice', compact('oc_cobi','socobiInvoices'));
      

  }

    public function add(Request $request)
    {

      $date = date("d.m.Y");
      $yearFull = (int)explode('.', $date)[2];
      $year = (int)substr($yearFull, -2);
      $month = (int)explode('.', $date)[1];

      if ($month < 4) {
          $prevYear = $year - 1;
          $fyid = " and ((m >= '4' and y = '$prevYear') or (m <= '3' and y='$year')) ";
          $fy = $prevYear . $year;
      } else {
          $nextYear = $year + 1;
          $fyid = " and ((m >= '4' and y = '$year') or (m <= '3' and y = '$nextYear')) ";
          $fy = $year . $nextYear;
      }

      $maxCobiincr = oc_cobi::max('cobiincr');

      $maxCobiincr = $maxCobiincr +1;
      if ($maxCobiincr < 10) {
          $inv = 'INV' . '-' . $fy . '-000' . $maxCobiincr;
      } elseif ($maxCobiincr < 100) {
          $inv = 'INV' .'-' . $fy . '-00' . $maxCobiincr;
      } else {
          $inv = 'INV' .'-' . $fy . '-0' . $maxCobiincr;
      }
      $company = contactdetails::where('active_flag', 1)->pluck('company')->first();
      $distinctVendors = oc_salesorder::select('vendor', 'vendorid')
      ->where('company', 'like', '%' . $company . '%')
      ->where('cobi_flag', 0)
      ->distinct()
      ->get();
  
  


    $gstin = contactdetails::value('gstin');

    $active_flag = contactdetails::value('active_flag');


if($active_flag == '1')
{
  $result_data = tcs_tax_percentage::where('active', 1)
  ->where('module', 'LIKE', '%O2C%')

  ->select('document', 'tax_percent', 'min_value', 'max_value')
  ->get()
  ->map(function($item) {
      return [
          "doc" => $item->document,
          "tax" => $item->tax_percent,
          "min" => $item->min_value,
          "max" => $item->max_value
      ];
  })
  ->toArray();
}
else{
  $result_data = [];
}





   return view('content.transactions.Invoice-add', compact('distinctVendors','inv','result_data','gstin'));
    }



    public function edit($id)
    {

        $oc_cobi = oc_cobi::where('invoice', $id)->get();

        $invoiceDetails = [];

        foreach ($oc_cobi as $order) {
            $invoiceDetails[] = [
                'category' => $order->cat,
                'description' => $order->description,
              
                'code' => $order->code,
                'tax' => $order->tax,
                'taxcode' => $order->taxcode,
                'taxvalue' => $order->taxvalue,
                'taxamount' => $order->taxamount,
          
                'quantity' => $order->quantity,
                'price' => $order->price,
                'taxable_price' => $order->taxable_price,
                'basic' => $order->basic,
                'narration' => $order->narration,
        'Total' => $order->totalwithtax,
                'totalquantity' => $order->totalquantity,
                'total' => $order->grandtotal,
             

            ];
        }

      
      
    

        $gstin = contactdetails::value('gstin');

        $active_flag = contactdetails::value('active_flag');

        // Retrieve the invoice ID
        $invoice = oc_cobi::where('invoice', $id)->first()->invoice;
        $narration = oc_cobi::where('invoice', $id)->first()->narration;
        $book_invoice = oc_cobi::where('invoice', $id)->first()->bookinvoice;

        $vendor = oc_cobi::where('invoice', $id)->first()->party;
        $vendorid = oc_cobi::where('invoice', $id)->first()->partycode;
        $irn = oc_cobi::where('invoice', $id)->first()->irn;
        $ewaybill = oc_cobi::where('invoice', $id)->first()->ewaybill;
        $so = oc_cobi::where('invoice', $id)->first()->so;

        $totalquantity = oc_cobi::where('invoice', $id)->first()->totalquantity;
        $total = oc_cobi::where('invoice', $id)->first()->grandtotal;

         $narration = oc_cobi::where('invoice', $id)->first()->narration;



        // Return the view with the data
        return view('content.transactions.Invoice-edit', compact('invoiceDetails', 'invoice','book_invoice','vendor','vendorid','totalquantity','total','so','totalquantity','total','narration','gstin','narration','irn','ewaybill'));
    }




    public function store(Request $request)
    {
        // Increment the max cobiincr value
        $maxCobiincr = oc_cobi::max('cobiincr') + 1;

        $activeContacts = contactdetails::where('active_flag', 1)->pluck('company')->first();


        // Validate the incoming request data
        $validatedData = $request->validate([
            'so' => 'required',
            'id' => 'required',
            'date' => 'required',
            'dist' => 'required',
            'invoice' => 'required',
      
            'category' => 'required|array',
            'description' => 'required|array',
            'code' => 'required|array',
            'quantity' => 'required|array',
            'price' => 'required|array',
            'taxable' => 'required|array',
            'basic' => 'required|array',
            'tax' => 'required|array',
            'tax_value' => 'required|array',
            'taxamount' => 'required|array',

            'Total' => 'required|array',
      
           

            'tquantity' => 'required',
            'total' => 'required',
         
        


        ]);


        foreach ($request->category as $index => $category) {

            $cobi = new oc_cobi();
            $cobi->so = $request->so;
            $cobi->partycode = $request->id;
            $cobi->date = $request->date;
            $cobi->party = $request->dist;
            $cobi->invoice = $request->invoice;
            $cobi->bookinvoice = $request->book_invoice;
            $cobi->totalquantity = $request->tquantity;
            $cobi->cobiincr = $maxCobiincr;
            $cobi->cat = $category;
         
            $cobi->description = $request->description[$index];
            $cobi->code = $request->code[$index];
            $cobi->quantity = $request->quantity[$index];
            $cobi->price = $request->price[$index];
            $cobi->taxable_price = $request->taxable[$index];
            $cobi->basic = $request->basic[$index];
            $cobi->taxcode = $request->tax[$index];
            $cobi->taxvalue = $request->tax_value[$index];
            $cobi->taxamount = $request->taxamount[$index];
            $cobi->totalwithtax = $request->Total[$index];
            $cobi->narration = $request->narration;




            if(($request->total - round($request->total)) != 0)
            {
                if(($request->total) >= round($request->total) ) {
                 $roundoff_type = "ADD";
                    $totalamount = round($request->total);
                    $roff = round(($request->total - round($request->total)),2);
                    $roffcrdr = "Dr";
                } else {
                  $roundoff_type = "DEDUCT";
                    $totalamount = round($request->total);
                    $roff = round((round($request->total) - $request->total) ,2);
                    $roffcrdr = "Cr";
                }
            }
            else
            
            {
                $roundoff_type = "";
            $totalamount = $request->total;
            $roff = 0;
            }
  
            $cobi->grandtotal = $totalamount;
            $cobi->company = $activeContacts;
            $cobi->round_type = $roundoff_type ;
            $cobi->round_off = $roff;
            $cobi->client = $activeContacts;
            $cobi->save();

            $adate = $request->date;
            $so = $request->invoice;
            // $grandtotal = $request->total;
            $type = "COBI";
            $vendor = $request->dist;
            $partycode = $request->id;
            $client = '';
            $empname = session()->get("valid_user");
            $globalwarehouse = '';


            $code = $request->code[$index];

            $quantity = $request->quantity[$index];
            $rateperunit = $request->price[$index];
             $taxcode = $request->tax[$index];
            $taxamount = $request->taxamount[$index];


            $itemCodes = ims_itemcodes::where('code', $code)->get();
           // $stdcost = ims_itemcodes::where('code', $code)->first()->stdcost;

                  $code1 = contactdetails::first()->ca;

              

        
               
            foreach ($itemCodes as $itemCode) {

                $cogsac = $itemCode->cogsac;
                $itemac = $itemCode->iac;
                $sac = $itemCode->sac;

            }
          // Replace 'your_taxcode_value' with your actual tax code
          $ta = DB::table('ims_taxcodes')
          ->where('description', $taxcode)
          ->value('coa');


          $stdcost = $rateperunit * $quantity;
    
       $itemcost = round($request->taxable[$index] * $rateperunit, 2);

          //  $stdcost = round($quantity * $rateperunit, 3);


            DB::table('ac_financialpostings')->insert([
                [
                    'date' => $adate,
                    'itemcode' => $code,
                    'crdr' => 'Cr',
                    'coacode' => $itemac,
                    'quantity' => $quantity,
                    'amount' =>  $stdcost,
                    'trnum' => $so,
                    'type' => $type,
                    'venname' => $vendor,
                    'venid' => $partycode,
                    'warehouse' => $globalwarehouse,
                    'client' => $activeContacts,
                    'empname' => $empname,
                    'created_at' => $adate
                ],
                [
                    'date' => $adate,
                    'itemcode' => $code,
                    'crdr' => 'Dr',
                    'coacode' => $cogsac,
                    'quantity' => $quantity,
                    'amount' => $stdcost,
                    'trnum' => $so,
                    'type' => $type,
                    'venname' => $vendor,
                    'venid' => $partycode,
                    'warehouse' => $globalwarehouse,
                     'client' => $activeContacts,
                    'empname' => $empname,
                    'created_at' => $adate
                ],
                [
                    'date' => $adate,
                    'itemcode' => '',
                    'crdr' => 'Cr',
                    'coacode' => $sac,
                    'quantity' => $quantity,
                    'amount' => $request->basic[$index],
                    'trnum' => $so,
                    'type' => $type,
                    'venname' => $vendor,
                    'venid' => $partycode,
                    'warehouse' => $globalwarehouse,
                    'client' => $activeContacts,
                    'empname' => $empname,
                    'created_at' => $adate
                ],
                [
                    'date' => $adate,
                    'itemcode' => $taxcode,
                    'crdr' => 'Cr',
                    'coacode' => $ta,
                    'quantity' => $quantity,
                    'amount' => $request->taxamount[$index],
                    'trnum' => $so,
                    'type' => $type,
                    'venname' => $vendor,
                    'venid' => $partycode,
                    'warehouse' => $globalwarehouse,
                     'client' => $activeContacts,
                    'empname' => $empname,
                    'created_at' => $adate
                ],
            [
                  'date' => $adate,
                  'itemcode' =>'' ,
                  'crdr' => 'Dr',
                  'coacode' => 'AS129',
                  'quantity' => $quantity,
                  'amount' => $totalamount,
                  'trnum' => $so,
                  'type' => $type,
                  'venname' => $vendor,
                  'venid' => $partycode,
                  'warehouse' => $globalwarehouse,
                  'client' => $activeContacts,
                  'empname' => $empname,
                  'created_at' => $adate
                ],
               

            ]);

            if ($roff > 0) {
                DB::table('ac_financialpostings')->insert([
                    'date' => $adate,
                    'itemcode' => '',
                    'crdr' => $roffcrdr,
                    'coacode' => 'ROFF101',
                    'quantity' => 0,
                    'amount' => round($roff, 2),
                    'trnum' => $so,
                    'type' => $type,
                    'venname' => $vendor,
                    'venid' => $partycode,
                    'warehouse' => $globalwarehouse,
                    'client' => $activeContacts,
                    'empname' => $empname,
                    'created_at' => $adate
                ]);
            }


        }


        DB::table('oc_salesorders')
            ->where('po', $request->so)
            ->update(['cobi_flag' => 1]);


        return redirect()->route('transctions-Invoice');
    }


    public function update(Request $request, $id)
{

    $validatedData = $request->validate([
        'irn' => 'required',
        'ewaybill' => 'required'
    ],
    [
        
        'irn.required' => 'The IRN field is required.',
        'ewaybill.required' => 'The Eway Bill no field is required',
 
    ]);

    // Find the existing oc_cobi records by invoice
    $cobiRecords = oc_cobi::where('invoice', $id)->get();

    // Update the common fields
    foreach ($cobiRecords as $index => $cobi) {
  
        $cobi->irn = $request->irn;
        $cobi->ewaybill = $request->ewaybill;
        $cobi->save();
    }





    return redirect()->route('transctions-Invoice');
}




  }
