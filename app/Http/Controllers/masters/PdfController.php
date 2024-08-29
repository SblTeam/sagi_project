<?php

namespace App\Http\Controllers\masters;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\PdfService;
use App\Models\oc_salesorder;
use App\Models\oc_cobi;
use App\Models\ims_itemcodes;
use App\Models\oc_packslip;
use App\Models\contactdetails;
use App\Models\cess_master;
use App\Models\oc_home_logo;
use App\Models\oc_tandc;
use Illuminate\Http\Request;

class PdfController extends Controller
{
    protected $pdfService;

    public function __construct(PdfService $pdfService)
    {
        $this->pdfService = $pdfService;
    }

    public function generatePdf($id)
    {
        $heading = "Tax Invoice";

        // Retrieve data from oc_cobi
        $cobi = oc_cobi::where('invoice', $id)->first();

        if ($cobi) {
            $inv_date = $cobi->addupdated;
            $tt_ttype = $cobi->taxtype;
            $cobi_flag1 = $cobi->cobi_flag;
            $party = $cobi->party;
            $partyid = $cobi->partycode;
            $date1 = date("d.m.y", strtotime($cobi->date));
            $freight = $cobi->freightamount;
            $freightcode = $cobi->freightcode;
            $freightcat = $cobi->freightcat;
            $vehnum = $cobi->vno;
            $esungam1 = $cobi->esungam;
            $taxamount = $cobi->taxamount;
            $discount = $cobi->discountamount;
            $finaltotal = $cobi->finaltotal;
            $destination = $cobi->destination;
            $bookinvoice = $cobi->bookinvoice;
            $narration = $cobi->remarks;
            $salestype = $cobi->salestype;
            $company = $cobi->company;
            $pono = $cobi->pono;
            $vno = $cobi->vno;
            $driver = $cobi->driver;
            $loaded_by = $cobi->loadedby;
            $ton = $cobi->totalweight;
            $tcs_tax = $cobi->tcs_tax;
            $podate = date("Y-m-d", strtotime($cobi->podate));
            $psno2 = $cobi->ps;

            // Retrieve date from oc_salesorder where po equals $pono
            $salesOrder = oc_salesorder::where('po', $pono)->first();
            $podateFromSalesOrder = $salesOrder ? date("Y-m-d", strtotime($salesOrder->date)) : null;

            // Retrieve time from addupdated column
            $time = oc_cobi::where('invoice', $id)
                ->selectRaw("TIME_FORMAT(addupdated, '%H:%i:%s') as t")
                ->value('t');

            // Determine podate2 value
            $podate2 = ($podate === '' || $podate === '1970-01-01') ? '' : date("d.m.Y", strtotime($podate));

            // Determine inv_type based on cobi_flag1
            $inv_type = ($cobi_flag1 == 1) ? "COBI" : "Direct Sales";



            $apiData = [
         
                 
                    'partyid' => $partyid,
                    'db' => session()->get("db")
      
            ];

      

            $apiData = json_encode($apiData);

            // Initialize cURL
            $apiUrl = 'https://secondary.sbl1972.in/secondarysales/apidistdestils.php';
            $ch = curl_init($apiUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPGET, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $apiData);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    
            // Execute cURL request
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $curlError = curl_error($ch);
            curl_close($ch);

            if ($httpCode === 200) {
                $apiData = json_decode($response, true);
    
    
            } 







            // Fetch contact details
            $contactname = contactdetails::value('name');
            $contctcompany = contactdetails::value('company');
            $contactaddress = contactdetails::value('address');
            $contactemail = contactdetails::value('email');
            $contactphoneno = contactdetails::value('phone');
            $contactgst = contactdetails::value('gstin');
            $contactstate = contactdetails::value('state');
            $logo = contactdetails::value('logo');
            $bank_name = contactdetails::value('bank_name');
            $branch_name = contactdetails::value('branch_name');
            $account_no = contactdetails::value('account_no');
            $IFSC = contactdetails::value('IFSC');
            $holder_name = contactdetails::value('holder_name');
      

            $oc_home_logo = oc_home_logo::where('company', $company)->first();

            // Initialize variables
            $fssl = $address = $signature = null;

            // Check if a record was found
            if ($oc_home_logo) {
                $fssl = $oc_home_logo->fssl;
                $address = $oc_home_logo->address;
                $signature = $oc_home_logo->signature;
            }

            $cess = 'yes';

            $items = oc_cobi::where('invoice', $id)->orderBy('id')->get();
            $htmlData = [];

            foreach ($items as $item) {
                $description = $item->description;
                $itemcode = $item->code;

                $itemDetails = ims_itemcodes::where('code', $itemcode)->first();
                $cat = $itemDetails->cat ?? '';
                $pieces = $itemDetails->pieces ?? 0;
                $hsn = $itemDetails->hsn ?? '';
                $sunits = $itemDetails->sunits ?? '';
                $bags = $item->quantity * $pieces;
                $units = $item->units;
                $price = $item->price;
                $tax = $item->taxamount;
                $taxvalue = $item->taxvalue;
                $totalwithtax = $item->totalwithtax;
                $cess_val = $item->cess_value;
                $amount = $item->total - $tax - $cess_val;
                $totala = $item->finaltotal;
                $amount1 = $bags * $price;
                $cess_tax = $item->cess_tax;

                $tax1 = $tax > 0 ? $tax : '0.00';
                $amount2 = $tax > 0 ? $amount1 : '0.00';
                $amount3 = $tax > 0 ? '0.00' : $amount1;

                $htmlData[] = [
                    'articleno' => '',
                    'description' => $description,
                    'hsn' => $hsn,
                    'quantity' => $item->quantity,
                    'units' => $sunits,
                    'taxable_price' => $item->taxable_price,
                    'taxable_amount' => $item->basic,
                    'taxamount' => $item->taxamount,
                    'price' => $price,
                    'totalwithtax' => $totalwithtax,
                    'grandtotal' => $item->grandtotal,
                ];
            }

            // Calculate totals and other necessary information
            $tqty = $items->sum('quantity');
            $tbags = collect($htmlData)->sum('bags');
            $fin_tax1 = $items->sum('taxamount');
            $totamount2 = collect($htmlData)->sum('amount2');
            $totamount3 = collect($htmlData)->sum('amount3');
            $ttlcess_val = $items->sum('cess_value');
            $total = $fin_tax1 + $totamount2 + $totamount3 + $ttlcess_val;

            $result = DB::table('oc_cobi as oc')
                ->join('ims_itemcodes as it', 'oc.code', '=', 'it.code')
                ->select(
                    'oc.code',
                    DB::raw('SUM(oc.quantity * oc.taxable_price) AS amt'),
                    DB::raw('SUM(oc.taxamount) AS taxamount1'),
                    'oc.taxvalue as taxvalue11',
                    'it.hsn as hsn11'
                )
                ->where('oc.invoice', $id)
                ->where('oc.taxamount', '>', 0)
                ->groupBy('it.hsn', 'oc.taxvalue', 'oc.code')
                ->get();

            // Initialize arrays to store data
            $data123 = [];

            // Iterate through the results and populate the arrays
            foreach ($result as $row) {
                $data123[] = [
                    'code' => $row->code,
                    'amt' => $row->amt,
                    'taxamount1' => $row->taxamount1,
                    'taxvalue11' => $row->taxvalue11,
                    'hsn11' => $row->hsn11,
                ];
            }
            $address123 = contactdetails::where('active_flag', 1)->pluck('address')->first();
            $place = contactdetails::where('active_flag', 1)->pluck('place')->first();
            $todaysdate = date('d-m-Y');
            return view('content.transactions.invoice-print', compact(
                'id',
                'inv_date',
                'tt_ttype',
                'cobi_flag1',
                'party',
                'partyid',
                'date1',
                'freight',
                'freightcode',
                'freightcat',
                'vehnum',
                'esungam1',
                'taxamount',
                'discount',
                'finaltotal',
                'destination',
                'bookinvoice',
                'narration',
                'salestype',
                'company',
                'pono',
                'vno',
                'driver',
                'loaded_by',
                'ton',
                'tcs_tax',
                'podate',
                'psno2',
                'podate2',
                'heading',
                'inv_type',
                'time',
            
                'fssl',
                'address',
                'signature',
                'cess',
              
                'htmlData',
                'tqty',
                'tbags',
                'fin_tax1',
                'totamount2',
                'totamount3',
                'ttlcess_val',
                'total',
                'data123',
                'address123',
                'place',
                'todaysdate',
                'contactname',
                'contctcompany',
                'contactaddress',
                'contactemail',
                'contactphoneno',
                'contactgst',
                'contactstate',
                'apiData',
                'logo',
                'bank_name',
                'branch_name',
                'holder_name',
                'IFSC',
                'account_no'

            ));
        }
    }
}
