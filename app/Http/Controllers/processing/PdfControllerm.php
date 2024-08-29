<?php

namespace App\Http\Controllers\processing;

use App\Http\Controllers\Controller;
use App\Services\PdfService;
use Illuminate\Http\Request;
use App\Models\oc_receipt;
use App\Models\contactdetails;
use Illuminate\Support\Number;

class PdfControllerm extends Controller
{
    protected $pdfService;

    public function __construct(PdfService $pdfService)
    {
        $this->pdfService = $pdfService;
    }
    public function generatePdf(Request $request)
    {
        $receipt = oc_receipt::where('tid',$request->id)->first();
        $contactDetail = ContactDetails::first();
        $receiptall = oc_receipt::where('tid',$request->id)->get();
        if($receipt->paymentmode=='Transfer'){$tran='UPI/Transfer :'.$receipt->upi;$cdate='';}
        else if($receipt->paymentmode=='Cheque'){$tran='Cheque No :'.$receipt->cheque;$cdate='Cheque Date :'.$receipt->cdate;}
        else{$tran='';$cdate='';$tranv='';$cdatev='';}
        $numword= ucwords(Number::spell($receipt->totalamount));
        $imageUrl = asset($contactDetail->logo);
        
        // echo $imageUrl;exit;
        $html ='<table width="105%" border="1" style="border-collapse:collapse;margin:-16px;border-color:black;border-style: solid;"><tr><td style="border:1px solid black;">
        <table width="100%" style="text-align:center;border:none;"><tr>
        <td width="20%" style="border:none;"><img src="'.$imageUrl.'" width="100%" /></td>
        <td width="60%" style="border:none;"><table width="100%" style="text-align:center;border-collapse:collapse;" >
        <tr><td style="border:none;font-weight:bold;font-size:18px;">'.$contactDetail->company.'</td></tr>
        <tr><td style="border:none;font-size:13px;text-align:left;padding:3px 85px 3px 110px;">Factory: '.$contactDetail->address.'</td></tr>
        <tr><td style="border:none;font-size:13px;text-align:left;padding:3px 85px 3px 110px;">Ph. No. : '.$contactDetail->phone.'</td></tr>
        <tr><td style="border:none;font-size:13px;text-align:left;padding:3px 85px 3px 110px;">GSTIN : '.$contactDetail->gstin.'</td></tr></table></td>
        <td width="20%" style="border:none;"></td></tr>
        <tr><td style="height:30px;border:none;" colspan="3"></td></tr>
        </table>
        </td></tr>
        <tr><td style="border:1px solid black;">
        <table width="100%" style="text-align:center;border-collapse:collapse;margin:-2px 0px;">
        <tr><td width="20%" style="border:none;"><span style="font-size:13px;text-align:left">Receipt. No: </span>'.$receipt->tid.'</td><td width="15%" style="border-color:black;border-style: solid;border-width: 0px 1px 0px 1px;"></td>
        <td width="30%" style="border:none;font-weight:bold;padding:10px;">RECEIPT</td><td width="15%" style="border-color:black;border-style: solid;border-width: 0px 1px 0px 1px;"></td>
        <td width="20%" style="border:none;"><span style="font-size:13px;text-align:left">DATE:</span> '.$receipt->date.'</td></tr></table>
        </td></tr>
        <tr><td style="border:1px solid black;">
        <table width="100%" style="text-align:left;border-collapse:collapse;">
        <tr><td width="65%" style="border:none;padding:8px;">Party : '.$receipt->party.'</td>
        <td width="35%" style="border:none;">Payment Mode : '.$receipt->paymentmode.'</td></tr></table>
        </td></tr>
        <tr><td style="border:1px solid black;">
        <table width="100%" style="text-align:center;border-collapse:collapse;margin:-2px 0px;">
        <tr><td style="height:8px;border:none;"></td><td style="border-color:black;border-style: solid;border-width: 0px 0px 0px 1px;"></td><td style="border-color:black;border-style: solid;border-width: 0px 0px 0px 1px;"></td></tr>
        <tr><td width="65%" style="border:none;font-weight:bold;">PARTICULARS</td>
        <td width="15%" style="border-color:black;border-style: solid;border-width: 0px 0px 0px 1px;">Cr/Dr</td>
        <td width="20%" style="border-color:black;border-style: solid;border-width: 0px 0px 0px 1px;">AMOUNT (Rs.)</td></tr></table>
        </td></tr>
        <tr><td style="border:1px solid black;">
        <table width="100%" style="text-align:center;border-collapse:collapse;margin:-2px 0px;">';
        if(count($receiptall)<=1){$fixel=70;}
        else if(count($receiptall)==2){$fixel=60;}
        else if(count($receiptall)==3){$fixel=50;}
        else if(count($receiptall)==4){$fixel=40;}
        else if(count($receiptall)==5){$fixel=30;}
        else if(count($receiptall)==6){$fixel=20;}
        else{$fixel=10;}
        foreach($receiptall as $rw){
        $html .='<tr><td width="65%" style="text-align:left;padding:5px 20px;border:none;">'.$rw->socobi.'</td>
        <td width="15%" style="border-color:black;border-style: solid;border-width: 0px 0px 0px 1px;">'.$rw->dr.'</td>
        <td width="20%" style="border-color:black;border-style: solid;border-width: 0px 0px 0px 1px;text-align:right;padding-right:20px;">'.indianmoney($rw->amountreceived).'</td></tr>';
        }
        $html .='<tr><td width="65%" style="text-align:left;border:none;height:'.$fixel.'px;"></td><td width="15%" style="border-color:black;border-style: solid;border-width: 0px 0px 0px 1px;"></td><td width="20%" style="border-color:black;border-style: solid;border-width: 0px 0px 0px 1px;"></td></tr>
        </table>
        </td></tr>
        <tr><td style="border:1px solid black;">
        <table width="100%" style="text-align:center;border-collapse:collapse;margin:-2px 0px;">
        <tr><td width="45%" style="border:none;text-align:left;padding:10px 2px 10px 10px;">'.$tran.'</td>
        <td width="20%" style="border:none;text-align:left;padding:10px 2px 10px 10px;">'.$cdate.'</td>
        <td width="15%" style="border-color:black;border-style: solid;border-width: 0px 0px 0px 1px;"></td>
        <td width="20%" style="border-color:black;border-style: solid;border-width: 0px 0px 0px 1px;"></td></tr>
        </table>
        </td></tr>
        <tr><td style="border:1px solid black;">
        <table width="100%" style="text-align:center;border-collapse:collapse;margin:-2px 0px;">
        <tr><td width="65%" style="border:none;text-align:left;padding:10px 10px 6px 10px;">Amount (in words) </td>
        <td width="15%" style="border:none;text-align:right;">TOTAL</td>
        <td width="20%" style="border-color:black;border-style: solid;border-width: 0px 0px 0px 1px;text-align:right;padding-right:20px;">'.indianmoney($receipt->totalamount).'</td></tr>
        <tr><td style="border:none;text-align:left;padding-left:10px;">'.$numword.'</td><td style="border:none;"></td><td style="border-color:black;border-style: solid;border-width: 0px 0px 0px 1px;""></td></tr>
        <tr><td style="height:20px;border:none;"></td><td style="border:none;"></td><td style="border-color:black;border-style: solid;border-width: 0px 0px 0px 1px;""></td></tr>
        </table>
        </td></tr>
         <tr><td style="border:1px solid black;"> 
        <table width="100%" style="text-align:center;border-collapse:collapse;">
        <tr><td width="60%" style="border:none;text-align:left;padding:10px;">Narration : '.$receipt->remarks.'</td>
        <td width="40%" style="border:none;font-size:13px;">Authorized Signature</td></tr>
        <tr><td style="height:40px;border:none;" colspan="2"></td></tr></table>
        </td></tr>
         <tr><td style="height:90px;border:1px solid black;"></td></tr>
        </table>';
        $output = $request->query('view', 'I');
        return $this->pdfService->generatePdf($html, $output);
    }
}
