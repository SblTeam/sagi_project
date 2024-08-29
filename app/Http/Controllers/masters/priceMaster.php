<?php

namespace App\Http\Controllers\masters;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ims_itemcodes;
use App\Models\oc_salesorder;
use App\Models\oc_loadingslip;

use App\Models\oc_pricemaster;
use App\Models\contactdetails;
use Illuminate\Support\Facades\Http;


class priceMaster extends Controller
{
  public function index()
  {
    $oc_pricemaster = oc_pricemaster::all();


    $db = session()->get("db");
    

    $apiData = json_encode($db);


    $apiUrl = 'https://secondary.sbl1972.in/secondarysales/getitemflag.php';
 
 

        $ch = curl_init($apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $apiData);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
             'Content-Type: application/json'
        ]);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_close($ch);

     // Execute cURL request
     $response = curl_exec($ch);
     $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
     $curlError = curl_error($ch);
     curl_close($ch);

   

     if ($httpCode == 200 && !$curlError) {
    
            $apiData = json_decode($response, true);

            return view('content.masters.PriceMaster', compact('oc_pricemaster','apiData'));
    

  }
}
  public function add()
  {

    $check_code = '';
    $date1 = date('d.m.Y');
    $date11 = date('Y-m-d', strtotime($date1));




    $activeContacts = contactdetails::where('active_flag', 1)->pluck('company')->first();
    $empname = session()->get("valid_user");

    $incr = oc_pricemaster::max('incr');
    $incr =  $incr +1;





    $items = DB::table('ims_itemcodes')
      ->select(
        DB::raw('DISTINCT(cat) as cat'),
        DB::raw("GROUP_CONCAT(CONCAT(code, '@', description, '@', cunits)) as cd")
      )
      ->where('halt_flag', 0)
      ->where('lel2flag', 1)

      ->groupBy('cat')
      ->get();

    return view('content.masters.priceMaster-add-edit', compact('items','activeContacts','incr','empname'));
  }

  public function edit(Request $request, $incr,$code)
  {

    $oc_pricemaster = oc_pricemaster::where('incr', $incr)->where('code', $code)->firstOrFail();

    $check_code = '';
    $date1 = date('d.m.Y');
    $date11 = date('Y-m-d', strtotime($date1));



    $activeContacts = contactdetails::where('active_flag', 1)->pluck('company')->first();

    $empname = session()->get("valid_user");

    $codes = oc_salesorder::select('code')
      ->distinct()
      ->pluck('code')
      ->toArray();



    $codeps = oc_loadingslip::select('code')
      ->distinct()
      ->pluck('code')
      ->toArray();



    $items = DB::table('ims_itemcodes')
      ->select(
        DB::raw('DISTINCT(cat) as cat'),
        DB::raw("GROUP_CONCAT(CONCAT(code, '@', description, '@', cunits)) as cd")
      )
      ->where('halt_flag', 0)
      ->where('iusage', 'LIKE', '%Sale%')

      ->groupBy('cat')
      ->get();

    $catp = $oc_pricemaster->cat;

    $description = ims_itemcodes::select('description')
      ->where('cat', $catp)
      ->get();

    $codep = ims_itemcodes::select('code')
      ->where('cat', $catp)
      ->get();

    return view('content.masters.priceMaster-add-edit', compact('items', 'oc_pricemaster', 'description', 'codep','activeContacts','incr','empname'));
  }




  public function store(Request $request)
  {
      // Validate incoming request data
      $validatedData = $request->validate([
          'category' => 'required|array',
          'description' => 'required|array',
          'code' => 'required|array',
          'units' => 'required|array',
          'price' => 'required|array',
          'client' => 'required|array',
          'date' => 'required',
          'empname' => 'required|array',
          'incr' => 'required',
      ]);
  
      try {
          // Start a database transaction
          DB::beginTransaction();
  
          // Initialize the incr value from the post view page
          $incr = $validatedData['incr'];
  
          // Prepare data for API request
          $apiData = [
              'category' => [],
              'description' => [],
              'code' => [],
              'units' => [],
              'price' => [],
              'client' => [],
              'date' => $validatedData['date'],
              'empname' => [],
              'incr' => []
     
          ];
   
          foreach ($validatedData['category'] as $index => $category) {
              if (
                  $category !== null &&
                  $validatedData['description'][$index] !== null &&
                  $validatedData['code'][$index] !== null &&
                  $validatedData['units'][$index] !== null &&
                  $validatedData['price'][$index] !== null
              ) {
                  $nn = new oc_pricemaster();
                  $nn->cat = $category;
                  $nn->desc = $validatedData['description'][$index];
                  $nn->code = $validatedData['code'][$index];
                  $nn->units = $validatedData['units'][$index];
                  $nn->price = $validatedData['price'][$index];
                  $nn->client = $validatedData['client'][$index];
                  $nn->date = $validatedData['date'];
                  $nn->empname = $validatedData['empname'][$index];
                  $nn->incr = $incr; // Set the current incr value
      
                  $nn->save();
  
                  // Add data to API request array
                  $apiData['category'][] = $category;
                  $apiData['description'][] = $validatedData['description'][$index];
                  $apiData['code'][] = $validatedData['code'][$index];
                  $apiData['units'][] = $validatedData['units'][$index];
                  $apiData['price'][] = $validatedData['price'][$index];
                  $apiData['client'][] = $validatedData['client'][$index];
                  $apiData['empname'][] = $validatedData['empname'][$index];
                  $apiData['incr'][] = $incr; // Add the current incr value
                  $apiData['db'][] = session()->get("db");
                  $incr++; // Increment the incr value
              }
          }


          $apiData = json_encode($apiData);


          // Commit the transaction
          DB::commit();
  
          // Send data to secondary server using cURL
          $apiUrl = 'https://secondary.sbl1972.in/secondarysales/savepricemasterapi.php';
          $ch = curl_init($apiUrl);
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
          curl_setopt($ch, CURLOPT_POST, true);
          curl_setopt($ch, CURLOPT_POSTFIELDS, $apiData);
          curl_setopt($ch, CURLOPT_HTTPHEADER, [
              'Content-Type: application/json'
          ]);
          curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
          curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  
          // Execute cURL request
          $response = curl_exec($ch);
          $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
          $curlError = curl_error($ch);
          curl_close($ch);
  
          // Debugging output

     
  
          if ($httpCode == 200 && !$curlError) {
              $responseData = json_decode($response, true);
  
              if ($responseData && isset($responseData['success']) && $responseData['success']) {
                  return redirect()
                      ->route('masters-PriceMaster')
                      ->with('success', 'Item saved successfully and data sent to secondary server!');
              } else {
                  return redirect()
                      ->route('masters-PriceMaster')
                      ->with('error', 'Item saved locally, but failed to send data to secondary server: ' . ($responseData['message'] ?? 'Unknown error'));
              }
          } else {
              return redirect()
                  ->route('masters-PriceMaster')
                  ->with('error', 'Item saved locally, but failed to send data to secondary server: ' . $curlError);
          }
      } catch (\Exception $e) {
          // Rollback the transaction on exception
          DB::rollBack();
  
          return redirect()
              ->route('masters-PriceMaster')
              ->with('error', 'An error occurred while saving data: ' . $e->getMessage());
      }
  }
  

  






  public function update(Request $request, $incr, $code)
  {


      // Define validation rules
      $validatedData = $request->validate([
        'category' => 'required|array',
        'description' => 'required|array',
        'code' => 'required|array',
        'units' => 'required|array',
        'price' => 'required|array',
        'client' => 'required|array',
        'date' => 'required',
        'empname' => 'required|array',
        'incr' => 'required',
      ]);
  
      try {
          // Start a database transaction
          DB::beginTransaction();
  
          // Prepare data for API request
          $apiData = [
            'category' => [],
            'description' => [],
            'code' => [],
            'units' => [],
            'price' => [],
            'client' => [],
            'date' => $validatedData['date'],
            'empname' => [],
            'incr' => []
          ];
  
          // Fetch records and update them
          foreach ($validatedData['category'] as $index => $category) {
            if (
                $category !== null &&
                $validatedData['description'][$index] !== null &&
                $validatedData['code'][$index] !== null &&
                $validatedData['units'][$index] !== null &&
                $validatedData['price'][$index] !== null
            ) {
                $nn = oc_pricemaster::where('incr', $incr)->where('code', $code)->firstOrFail();
                $nn->cat = $category;
                $nn->desc = $validatedData['description'][$index];
                $nn->code = $validatedData['code'][$index];
                $nn->units = $validatedData['units'][$index];
                $nn->price = $validatedData['price'][$index];
                $nn->client = $validatedData['client'][$index];
                $nn->date = $validatedData['date'];
                $nn->empname = $validatedData['empname'][$index];
                $nn->incr = $incr; // Set the current incr value
    
                $nn->save();

                // Add data to API request array
                $apiData['category'][] = $category;
                $apiData['description'][] = $validatedData['description'][$index];
                $apiData['code'][] = $validatedData['code'][$index];
                $apiData['units'][] = $validatedData['units'][$index];
                $apiData['price'][] = $validatedData['price'][$index];
                $apiData['client'][] = $validatedData['client'][$index];
                $apiData['empname'][] = $validatedData['empname'][$index];
                $apiData['incr'][] = $incr; // Add the current incr value

                $incr++; // Increment the incr value
            }
        }
  
          $apiData = json_encode($apiData);

          // Commit the transaction
          DB::commit();
  
          // Send data to secondary server using cURL
          $apiUrl = 'https://secondary.sbl1972.in/secondarysales/updatepricemasterapi.php';
          $ch = curl_init($apiUrl);
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
          curl_setopt($ch, CURLOPT_POST, true);
          curl_setopt($ch, CURLOPT_POSTFIELDS, $apiData);
          curl_setopt($ch, CURLOPT_HTTPHEADER, [
              'Content-Type: application/json'
          ]);
          curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
          curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  
          // Execute cURL request
          $response = curl_exec($ch);
          $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
          $curlError = curl_error($ch);
          curl_close($ch);
  
          if ($httpCode == 200 && !$curlError) {
              $responseData = json_decode($response, true);
  
              if ($responseData && isset($responseData['success']) && $responseData['success']) {
                  return redirect()
                      ->route('masters-PriceMaster')
                      ->with('success', 'Item updated successfully and data sent to secondary server!');
              } else {
                  return redirect()
                      ->route('masters-PriceMaster')
                      ->with('error', 'Item updated locally, but failed to send data to secondary server: ' . ($responseData['message'] ?? 'Unknown error'));
              }
          } else {
              return redirect()
                  ->route('masters-PriceMaster')
                  ->with('error', 'Item updated locally, but failed to send data to secondary server: ' . $curlError);
          }
      } catch (\Exception $e) {
          // Rollback the transaction on exception
          DB::rollBack();
  
          return redirect()
              ->route('masters-PriceMaster')
              ->with('error', 'An error occurred while updating data: ' . $e->getMessage());
      }
  }
  
  



  public function destroy($incr, $code)
  {
   
      try {
          // Start a database transaction
          DB::beginTransaction();
  
          // Find the record based on 'incr' and 'code' and delete it
          $oc_pricemaster = oc_pricemaster::where('incr', $incr)->where('code', $code)->firstOrFail();
          $oc_pricemaster->delete();
          $db = session()->get("db");
          // Commit the transaction
          DB::commit();
  
          // Prepare data to send to secondary server
          $data = ['incr' => $incr, 'code' => $code, 'db' => $db];
          $apiData = json_encode($data);
  

          // Send data to secondary server using cURL
          $apiUrl = 'https://secondary.sbl1972.in/secondarysales/deletepricemasterapi.php';
          $ch = curl_init($apiUrl);
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
          curl_setopt($ch, CURLOPT_POST, true);
          curl_setopt($ch, CURLOPT_POSTFIELDS, $apiData);
          curl_setopt($ch, CURLOPT_HTTPHEADER, [
               'Content-Type: application/json'
          ]);
          curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
          curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  
          // Execute cURL request
          $response = curl_exec($ch);
          $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
          $curlError = curl_error($ch);
          curl_close($ch);
  
          // Debugging output
          echo "API Data Sent: " . print_r($data, true) . "\n";
          echo "Response Code: " . $httpCode . "\n";
          echo "Response: " . $response . "\n";
          echo "cURL Error: " . $curlError . "\n";
     
  
          if ($httpCode == 200 && !$curlError) {
              $responseData = json_decode($response, true);
  
              if ($responseData && isset($responseData['success']) && $responseData['success']) {
                  return redirect()
                      ->route('masters-PriceMaster')
                      ->with('success', 'Item deleted successfully and data sent to secondary server!');
              } else {
                  return redirect()
                      ->route('masters-PriceMaster')
                      ->with('error', 'Item deleted locally, but failed to delete data from secondary server: ' . ($responseData['message'] ?? 'Unknown error'));
              }
          } else {
              return redirect()
                  ->route('masters-PriceMaster')
                  ->with('error', 'Item deleted locally, but failed to delete data from secondary server: ' . $curlError);
          }
      } catch (\Exception $e) {
          // Rollback the transaction on exception
          DB::rollBack();
  
          return redirect()
              ->route('masters-PriceMaster')
              ->with('error', 'An error occurred while deleting data: ' . $e->getMessage());
      }
  }
  


}
