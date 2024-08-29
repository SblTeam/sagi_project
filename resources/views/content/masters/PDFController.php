<?php
namespace App\Http\Controllers\masters;

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

            $ps = '';
            $pdate = '';
            $so = '';
            $sdate = '';

            // Fetch contact details
            $gstin = contactdetails::value('gstin');
            $state = contactdetails::value('state');

            $oc_home_logo = oc_home_logo::where('company', $company)->first();

            // Initialize variables
            $fssl = null;
            $address = null;
            $signature = null;

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
                    'bags' => $bags,
                    'price' => $price,
                    'taxvalue' => $taxvalue,
                    'tax1' => $tax1,
                    'cess_per' => '',
                    'cess_val' => '',
                    'amount2' => $amount2,
                    'amount3' => $amount3,
                    'totalwithtax' => $totalwithtax,
                    // Other necessary fields
                ];
            }

            // Calculate totals and other necessary information
            $tqty = $items->sum('quantity');
            $tbags = collect($htmlData)->sum('bags'); // Use collect() to create a collection
            $fin_tax1 = $items->sum('taxamount');
            $totamount2 = collect($htmlData)->sum('amount2'); // Use collect() to create a collection
            $totamount3 = collect($htmlData)->sum('amount3'); // Use collect() to create a collection
            $ttlcess_val = $items->sum('cess_value');
            $total = $fin_tax1 + $totamount2 + $totamount3 + $ttlcess_val;

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
                'gstin',
                'fssl',
                'address',
                'signature',
                'cess',
                'state',
                'htmlData',
                'tqty',
                'tbags',
                'fin_tax1',
                'totamount2',
                'totamount3',
                'ttlcess_val',
                'total'
            ));
        }
    }
}
