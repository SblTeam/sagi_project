<?php
namespace App\Http\Controllers\Transactions;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\oc_salesorder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class getso extends Controller
{


  public function fetchsoDetails (Request $request)
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
    $maxsoincr = 0;
$id = $request->input('id');

$idno = explode("-",$id)[1];

 $sosr = 'SO-'.$idno.'-'.$fy.'-';

$maxsoincr = oc_salesorder::where('po', 'like', "$sosr%")->max('poincr');





 $maxsoincr = $maxsoincr + 1;



if ( $maxsoincr < 10)
{  $so = 'SO-'.$idno.'-'.$fy.'-000'.$maxsoincr;


}

else if($maxsoincr < 100 && $maxsoincr >= 10) {  $so = 'SO-'.$idno.'-'.$fy.'-00'.$maxsoincr;  }

else {  $so = 'SO-'.$idno.'-'.$fy.'-0'.$maxsoincr;  }

return $so;

  }



}
