<?php

namespace App\Http\Controllers\Transactions;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\oc_salesorder;
use App\Models\contactdetails;
use Illuminate\Support\Facades\Http;

class SalesOrder extends Controller
{

  public function index()
  {
      $oc_salesorder = oc_salesorder::select('oc_salesorders.*')
                                     ->join(DB::raw('(SELECT MIN(id) as id FROM oc_salesorders GROUP BY po) as grouped'), 'oc_salesorders.id', '=', 'grouped.id')
                                     ->get();

      return view('content.transactions.SalesOrder', compact('oc_salesorder'));
  }

  public function add(Request $request)
  {
      try {
          // Determine current date and fiscal year
          $date = date("d.m.Y");
          $yearFull = (int)explode('.', $date)[2];
          $year = (int)substr($yearFull, -2);
          $month = (int)explode('.', $date)[1];
  
          if ($month < 4) {
              $prevYear = $year - 1;
              $fy = $prevYear . $year;
          } else {
              $nextYear = $year + 1;
              $fy = $year . $nextYear;
          }
  
          // Get the count of distinct purchase orders
          $table = "oc_salesorders";
          $qry2 = "SELECT count(DISTINCT po) as total FROM $table";
          $result2 = DB::select($qry2);
          $poincr = $result2[0]->total + 1;
  
          // Format purchase order number
          $po = 'SO-' . $fy . '-' . str_pad($poincr, 4, '0', STR_PAD_LEFT);
       
    $activeContacts = contactdetails::where('active_flag', 1)->pluck('company')->first();

          $apiData = [
     
        
            'vendor' => $activeContacts,
            'db' => session()->get("db")
        
    ];
          $apiData = json_encode($apiData);


          // Initialize cURL
          $apiUrl = 'https://secondary.sbl1972.in/secondarysales/apidistnameid.php';
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
  
          if ($httpCode == 200 && !$curlError) {
              // Decode JSON response into associative array
              $data = json_decode($response, true);
          } else {
              // Handle failed API response
              return redirect()
                  ->route('masters-PriceMaster')
                  ->with('error', 'Failed to fetch distributor data from secondary server: ' . $curlError);
          }
  
          // Return the view with the generated purchase order and distributor data
          return view('content.transactions.SalesOrder1-add', compact('po', 'data'));
  
      } catch (\Exception $e) {
          // Handle exceptions and return an error message
          return redirect()
              ->route('masters-PriceMaster')
              ->with('error', 'An error occurred: ' . $e->getMessage());
      }
  }
  
  
  

  public function store(Request $request)
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

      $table = "oc_salesorders";

      $activeContacts = contactdetails::where('active_flag', 1)->pluck('company')->first();
      $validatedData = $request->validate([
        'so' => 'required',
        'id' => 'required',
        'date' => 'required',
        'distributor' => 'required',
        'po' => 'required',
        'category' => 'required|array',
        'description' => 'required|array',
        'code' => 'required|array',
        'quantity' => 'required|array',
        'enteredquantity' =>'required|array',
       
        'price' => 'required|array',
        'taxableprice' => 'required|array',
        'basic_total' => 'required|array',
        'taxType' => 'required|array',
        'tax' => 'required|array',
        'taxamount' => 'required|array',
        'total_amount' => 'required|array',
        'tquantity' => 'required',
        'total' => 'required',
    ]);
 
    $nn1 = new oc_salesorder();
    $nn1->vendorid = $validatedData['id'];


    $idno = explode("-",$nn1->vendorid)[1];

 $sosr = 'SO-'.$idno.'-'.$fy.'-';

$maxsoincr = oc_salesorder::where('po', 'like', "$sosr%")->max('poincr');





 $maxsoincr = $maxsoincr + 1;



      // Iterate through the rows and insert into the database
      foreach ($request->category as $index => $category) {
          if (isset($request->check[$index])) {
            if($request->enteredquantity[$index]>0)
            {
                $nn = new \App\Models\oc_salesorder(); // Make sure to use the correct namespace
                $nn->po = $request->so;
                $nn->vendorid = $request->id;
                $nn->date = $request->date;
                $nn->vendor = $request->distributor;
                $nn->pono = $request->po;
                $nn->tquantity = $request->tquantity;
                $nn->total = $request->total;
                $nn->category = $category;
                $nn->description = $request->description[$index];
                $nn->code = $request->code[$index];
                $nn->quantity = $request->quantity[$index];
                $nn->squantity = $request->enteredquantity[$index];
                $nn->sprice = $request->price[$index];
                $nn->taxableprice = $request->taxableprice[$index];
                $nn->basic = $request->basic_total[$index];
                $nn->taxcode = $request->taxType[$index];
                $nn->taxvalue = $request->tax[$index];
                $nn->taxamount = $request->taxamount[$index];
                $nn->totalwithtax = $request->total_amount[$index];
                $nn->company = $activeContacts;
                $nn->client = $activeContacts;
                $nn->empname  = session()->get("valid_user");
                $nn->poincr = $maxsoincr;
                $nn->save();
            }
           
          }
      }

      // Prepare data for API request
      $apiData = [
          'dataset' => [
              'flag' => 1,
              'po' => $request->po,
              'db' => session()->get("db")
          ]
      ];
      $apiData = json_encode($apiData);

      // Initialize cURL session
      $ch = curl_init();

      // Set cURL options
      curl_setopt($ch, CURLOPT_URL, 'https://secondary.sbl1972.in/secondarysales/poflagupdate.php');
      curl_setopt($ch, CURLOPT_POST, true);
      curl_setopt($ch, CURLOPT_POSTFIELDS,$apiData);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

      // Execute cURL request
      $response = curl_exec($ch);

      // Get HTTP status code
      $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

      // Get cURL error if any
      $curlError = curl_error($ch);

      // Close cURL session
      curl_close($ch);

      if ($httpCode == 200 && !$curlError) {
          // Optional: Handle successful response if needed
      } else {
          // Handle failed API response
          return redirect()
              ->route('transctions-SalesOrder')
              ->with('error', 'Failed to update PO flag: ' . $curlError);
      }

      // Redirect to the route
      return redirect()->route('transctions-SalesOrder');
  }



  public function update(Request $request, $code)
  {
      // Fetch the existing sales orders
      $oc_salesorders = \App\Models\oc_salesorder::where('po', $code)->get();

      // Validate the incoming request data
      $validatedData = $request->validate([
          'so' => 'required',
          'id' => 'required',
          'date' => 'required',
          'distributor' => 'required',
          'po' => 'required',
          'category' => 'required|array',
          'description' => 'required|array',
          'code' => 'required|array',
          'quantity' => 'required|array',
          'enteredquantity' => 'array',
          'price' => 'required|array',
          'taxableprice' => 'required|array',
          'basic_total' => 'required|array',
          'taxType' => 'required|array',
          'tax' => 'required|array',
          'taxamount' => 'required|array',
          'totalamount' => 'required|array',
          'tquantity' => 'required',
          'total' => 'required',
      ]);



      // Delete existing sales orders
      \App\Models\oc_salesorder::where('po', $code)->delete();

      // Iterate through the rows and insert the updated data into the database
      foreach ($request->category as $index => $category) {
          if (isset($request->check[$index])) {
              $oc_salesorder = new \App\Models\oc_salesorder(); 
              $oc_salesorder->po = $request->so;
              $oc_salesorder->vendorid = $request->id;
              $oc_salesorder->date = $request->date;
              $oc_salesorder->vendor = $request->distributor;
              $oc_salesorder->pono = $request->po;
              $oc_salesorder->tquantity = $request->tquantity;
              $oc_salesorder->total = $request->total;
              $oc_salesorder->category = $category;
              $oc_salesorder->description = $request->description[$index];
              $oc_salesorder->code = $request->code[$index];
              $oc_salesorder->quantity = $request->quantity[$index];
              $oc_salesorder->squantity = $request->enteredquantity[$index] ?? null;
              $oc_salesorder->sprice = $request->price[$index];
              $oc_salesorder->taxableprice = $request->taxableprice[$index];
              $oc_salesorder->basic = $request->basic_total[$index];
              $oc_salesorder->taxcode = $request->taxType[$index];
              $oc_salesorder->taxvalue = $request->tax[$index];
              $oc_salesorder->taxamount = $request->taxamount[$index];
              $oc_salesorder->totalwithtax = $request->totalamount[$index];
              $oc_salesorder->save();


          }
      }

      // Prepare data for API request
      $apiData = [
          'dataset' => [
              'flag' => 1,
              'po' => $request->po,
              'db' => session()->get("db")
          ]
      ];
      $apiData = json_encode($apiData);
      // Initialize cURL session
      $ch = curl_init();

      // Set cURL options
      curl_setopt($ch, CURLOPT_URL, 'https://secondary.sbl1972.in/secondarysales/poflagupdate.php');
      curl_setopt($ch, CURLOPT_POST, true);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $apiData);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

      // Execute cURL request
      $response = curl_exec($ch);

      // Get HTTP status code
      $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

      // Get cURL error if any
      $curlError = curl_error($ch);

      // Close cURL session
      curl_close($ch);

      if ($httpCode == 200 && !$curlError) {
          // Optional: Handle successful response if needed
      } else {
          // Handle failed API response
          return redirect()
              ->route('transctions-SalesOrder')
              ->with('error', 'Failed to update PO flag: ' . $curlError);
      }

      // Redirect back with a success message
      return redirect()
          ->route('transctions-SalesOrder')
          ->with('success', 'Sales order updated successfully!');
  }



    public function edit($id)
    {
        // Retrieve sales order rows for the given PO ID
        $oc_salesorder = oc_salesorder::where('po', $id)->get();
$data = [];
        // Initialize the associative array
        $salesOrderDetails = [];

        // Loop through each sales order row
        foreach ($oc_salesorder as $order) {
            $salesOrderDetails[] = [
                'category' => $order->category,
                'description' => $order->description,
                'code' => $order->code,
                'taxtype' => $order->taxtype,
                'tax' => $order->tax,
                'quantity' => $order->quantity,
                'squantity' => $order->squantity,
                'price' => $order->sprice,
                'taxableprice' => $order->taxableprice,
                'basic_total' => $order->basic,
             
                'taxcode' => $order->taxcode,
                'taxvalue' => $order->taxvalue,
                'taxamount' => $order->taxamount,
                'total_amount' => $order->totalwithtax,
                'taxvalue' => $order->taxvalue,
                // Add other fields as necessary
            ];
        }


        // Get other required details
        $pono = oc_salesorder::where('po', $id)->first()->pono;
        $vendor = oc_salesorder::where('po', $id)->first()->vendor;
        $vendorid = oc_salesorder::where('po', $id)->first()->vendorid;

        $tquantity = oc_salesorder::where('po', $id)->first()->tquantity;
        $total = oc_salesorder::where('po', $id)->first()->total;

        // Pass data to the view
        return view('content.transactions.SalesOrder-edit', compact('salesOrderDetails', 'id', 'pono', 'vendor', 'vendorid', 'data', 'tquantity', 'total'));
    }


    public function destroy($id)
    {
        // Fetch the `pono` for the given `po`
        $pono = \App\Models\oc_salesorder::where('po', $id)->first()->pono;

        // Delete the record with the given `po`
        \App\Models\oc_salesorder::where('po', $id)->delete();

        // Prepare data for API request
        $apiData = [
            'dataset' => [
                'flag' => 0,
                'po' => $pono,
                'db' => session()->get("db")
            ]
        ];
        $apiData = json_encode($apiData);
        // Initialize cURL session
        $ch = curl_init();

        // Set cURL options
        curl_setopt($ch, CURLOPT_URL, 'https://secondary.sbl1972.in/secondarysales/poflagupdate.php');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $apiData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        // Execute cURL request
        $response = curl_exec($ch);

        // Get HTTP status code
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        // Get cURL error if any
        $curlError = curl_error($ch);

        // Close cURL session
        curl_close($ch);

        if ($httpCode == 200 && !$curlError) {
            // Optional: Handle successful response if needed
        } else {
            // Handle failed API response
            return redirect()
                ->route('transctions-SalesOrder')
                ->with('error', 'Failed to update PO flag: ' . $curlError);
        }

        // Redirect back with a success message
        return redirect()
            ->route('transctions-SalesOrder')
            ->with('success', 'Item(s) deleted successfully!');
    }



  }
