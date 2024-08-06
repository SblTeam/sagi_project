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

    return view('content.masters.PriceMaster', compact('oc_pricemaster'));
  }
  public function add()
  {
    $check_code = '';
    $date1 = date('d.m.Y');
    $date11 = date('Y-m-d', strtotime($date1));




    $activeContacts = contactdetails::where('active_flag', 1)->pluck('name')->first();

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

    return view('content.masters.priceMaster-add-edit', compact('items','activeContacts','incr'));
  }

  public function edit(Request $request, $incr,$code)
  {

    $oc_pricemaster = oc_pricemaster::where('incr', $incr)->where('code', $code)->firstOrFail();

    $check_code = '';
    $date1 = date('d.m.Y');
    $date11 = date('Y-m-d', strtotime($date1));



    $activeContacts = contactdetails::where('active_flag', 1)->pluck('name')->first();

    $codes = oc_salesorder::select('code')
      ->distinct()
      ->pluck('code')
      ->toArray();

   

    $codeps = oc_loadingslip::select('code')
      ->distinct()
      ->pluck('code')
      ->toArray();

    $check_code_array = array_merge($codes, $codeds, $codeps);

    $items = DB::table('ims_itemcodes')
      ->select(
        DB::raw('DISTINCT(cat) as cat'),
        DB::raw("GROUP_CONCAT(CONCAT(code, '@', description, '@', cunits)) as cd")
      )
      ->where('halt_flag', 0)
      ->where('iusage', 'LIKE', '%Sale%')
      ->whereNotIn('code', $check_code_array)
      ->groupBy('cat')
      ->get();

    $catp = $oc_pricemaster->cat;

    $description = ims_itemcodes::select('description')
      ->where('cat', $catp)
      ->get();

    $codep = ims_itemcodes::select('code')
      ->where('cat', $catp)
      ->get();

    return view('content.masters.priceMaster-add-edit', compact('items', 'oc_pricemaster', 'description', 'codep','activeContacts','incr'));
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
                  $nn->incr = $incr; // Set the current incr value
                  $nn->save();

                  // Add data to API request array
                  $apiData['category'][] = $category;
                  $apiData['description'][] = $validatedData['description'][$index];
                  $apiData['code'][] = $validatedData['code'][$index];
                  $apiData['units'][] = $validatedData['units'][$index];
                  $apiData['price'][] = $validatedData['price'][$index];
                  $apiData['client'][] = $validatedData['client'][$index];
                  $apiData['incr'][] = $incr; // Add the current incr value

                  $incr++; // Increment the incr value
              }
          }

          // Commit the transaction
          DB::commit();

          // Send data to secondary server
          $apiUrl = 'https://secondary.sbl1972.in/secondarysales/savepricemasterapi.php';
          $response = Http::post($apiUrl, $apiData);

          if ($response->successful()) {
              return redirect()
                  ->route('masters-PriceMaster')
                  ->with('success', 'Item saved successfully and data sent to secondary server!');
          } else {
              return redirect()
                  ->route('masters-PriceMaster')
                  ->with('error', 'Item saved locally, but failed to send data to secondary server.');
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
          'incr' => 'required',
      ]);

      try {
          // Start a database transaction
          DB::beginTransaction();

          // Fetch the record based on 'incr' and 'code'
          $nn = oc_pricemaster::where('incr', $incr)->where('code', $code)->firstOrFail();

          foreach ($validatedData['category'] as $index => $category) {
              // Update the record with the validated data
              $nn->cat = $category;
              $nn->desc = $validatedData['description'][$index];
              $nn->code = $validatedData['code'][$index];
              $nn->units = $validatedData['units'][$index];
              $nn->price = $validatedData['price'][$index];
              $nn->client = $validatedData['client'][$index];
              $nn->date = $validatedData['date'];
              $nn->incr = $validatedData['incr'];

              $nn->save();
          }

          // Commit the transaction
          DB::commit();

          // Prepare data to send to secondary server
          $apiData = [
              'category' => $validatedData['category'],
              'description' => $validatedData['description'],
              'code' => $validatedData['code'],
              'units' => $validatedData['units'],
              'price' => $validatedData['price'],
              'client' => $validatedData['client'],
              'date' => $validatedData['date'],
              'incr' => $validatedData['incr'],

          ];

          // Send data to secondary server
          $apiUrl = 'https://secondary.sbl1972.in/secondarysales/updatepricemasterapi.php';
          $response = Http::post($apiUrl, $apiData);

          if ($response->successful()) {
              return redirect()
                  ->route('masters-PriceMaster')
                  ->with('success', 'Item updated successfully and data sent to secondary server!');
          } else {
              return redirect()
                  ->route('masters-PriceMaster')
                  ->with('error', 'Item updated locally, but failed to send data to secondary server.');
          }
      } catch (\Exception $e) {
          // Rollback the transaction on exception
          DB::rollBack();

          return redirect()
              ->route('masters-PriceMaster')
              ->with('error', 'An error occurred while updating data: ' . $e->getMessage());
      }
  }



  public function destroy($incr,$client)
  {
      try {

          DB::beginTransaction();
          $oc_pricemaster = oc_pricemaster::where('incr', $incr)->where('client', $client)->firstOrFail();
          $oc_pricemaster->delete();


          DB::commit();


          $data = ['incr' => $incr,'client' => $client];


          $apiUrl = 'https://secondary.sbl1972.in/secondarysales/deletepricemasterapi.php';
          $response = Http::post($apiUrl, $data);

          if ($response->successful()) {
              return redirect()
                  ->route('masters-PriceMaster')
                  ->with('success', 'Item deleted successfully and data sent to secondary server!');
          } else {
              return redirect()
                  ->route('masters-PriceMaster')
                  ->with('error', 'Item deleted locally, but failed to delete data from secondary server.');
          }
      } catch (\Exception $e) {

          DB::rollBack();

          return redirect()
              ->route('masters-PriceMaster')
              ->with('error', 'An error occurred while deleting data: ' . $e->getMessage());
      }
  }


}
