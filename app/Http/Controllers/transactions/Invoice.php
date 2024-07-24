<?php

namespace App\Http\Controllers\Transactions;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\oc_salesorder;
use App\Models\pp_sobi;
use App\Models\tcs_tax_percentage;
use Illuminate\Support\Facades\Http;

class Invoice extends Controller
{



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

      $maxSobiincr = pp_sobi::max('sobiincr');

      $maxSobiincr = $maxSobiincr +1;
      if ($maxSobiincr < 10) {
          $inv = 'INV' . '-' . $fy . '-000' . $maxSobiincr;
      } elseif ($maxSobiincr < 100) {
          $inv = 'INV' .'-' . $fy . '-00' . $maxSobiincr;
      } else {
          $inv = 'INV' .'-' . $fy . '-0' . $maxSobiincr;
      }
      $distinctVendors = oc_salesorder::select('vendor', 'vendorid')
    ->distinct()
    ->get();

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


   return view('content.transactions.Invoice-add', compact('distinctVendors','inv','result_data'));
    }



  }
