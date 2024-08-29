<?php

namespace App\Http\Controllers\masters;

use Illuminate\Support\Facades\DB;  // Ensure this is included
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Imports\ImportUser;
use App\Models\ims_itemcodes;
use App\Models\ims_itemtypes;
use App\Models\ims_itemunits;
use App\Models\ims_taxcodes;
use App\Models\contactdetails;
use Illuminate\Validation\Rule;
use App\Models\ac_coa;
use Maatwebsite\Excel\Facades\Excel;

class ItemMaster extends Controller
{
  public function index()
  {
    $ims_itemcodes = ims_itemcodes::all();

    return view('content.masters.ItemMaster', compact('ims_itemcodes'));
  }




  public function add()
  {
    $categoryGroups = DB::table('ims_itemtypes')
      ->select(DB::raw('GROUP_CONCAT(type) as type'), 'catgroup')
      ->groupBy('catgroup')
      ->get();


    $categorytypes = ims_itemtypes::select('catgroup', 'type')
      ->get();

    $codec = ac_coa::select('code', 'description')
      ->where('type', 'Asset')
      ->where('schedule', 'Inventories')
      ->orderBy('code', 'ASC')
      ->get();


    $codee = ac_coa::select('code', 'description')
      ->where('type', 'Expense')
      ->where('code','like', 'CG%')
      ->orderBy('code', 'ASC')
      ->get();

      $codsr = ac_coa::select('code', 'description')
      ->where('type', 'Expense')
      ->where('code','like', 'SR%')
      ->orderBy('code', 'ASC')
      ->get();




    $codes = ac_coa::select('code', 'description')
      ->where('type', 'Revenue')
      ->where('schedule', 'Revenue From Operations')
      ->orderBy('code', 'ASC')
      ->get();



    $sunits = ims_itemunits::select('sunits')
      ->get()
      ->unique('sunits');

    $taxcode = ims_taxcodes::select('code')
      ->where('code', 'like', '%gst%')

      ->get()
      ->unique('code');


    $sunits1 = ims_itemunits::where('sunits', 'bags')
      ->select('sunits')
      ->get()
      ->unique('sunits');
    return view('content.masters.ItemMaster-add-edit', compact('categoryGroups', 'sunits', 'sunits1', 'taxcode', 'codec', 'codee', 'codes', 'categorytypes','codsr'));
  }



  public function saveimport(Request $request)
  {
      // Validate the file input
      $request->validate([
          'file' => 'required|file|mimes:xlsx,xls,csv|max:2048',
      ]);
  
      $import = new ImportUser();
  
      try {
          // Handle the file
          $file = $request->file('file');
          $filePath = $file->store('files'); // Store file and get the relative path
  




          // Import the file
          Excel::import($import, storage_path('app/' . $filePath)); // Use absolute path for import
  
          // Check if there are any errors collected during import
          $errors = $import->getErrors() ?? []; // Use null coalescing to avoid errors if getErrors is not defined
  
          if (count($errors) > 0) {
              // Redirect with errors if present
              return redirect()
                  ->route('masters.ItemMaster.import')
                  ->with('error', 'Some rows have errors. Please review the import file and try again.')
                  ->with('import_errors', $errors);
          }
  
          // Success message if no errors
          return redirect()
              ->route('masters-ItemMaster')
              ->with('success', 'Items saved successfully!');
      } catch (\Exception $e) {
          // Redirect with error if an exception occurs
          return redirect()
              ->route('masters.ItemMaster.import')
              ->with('error', 'Error processing the import file: ' . $e->getMessage());
      }
  }
  
  

  public function import()
  {
    return view('content.masters.ItemMaster-import');
  }

  public function edit($id)
  {
    $categoryGroups = DB::table('ims_itemtypes')
      ->select(DB::raw('GROUP_CONCAT(type) as type'), 'catgroup')
      ->groupBy('catgroup')
      ->get();

    $categorytypes = ims_itemtypes::select('catgroup', 'type')
      ->get();

    $codec = ac_coa::select('code', 'description')
      ->where('type', 'Asset')
      ->where('schedule', 'Inventories')
      ->orderBy('code', 'ASC')
      ->get();


    $codee = ac_coa::select('code', 'description')
      ->where('type', 'Expense')
      ->where('code','like', 'CG%')
      ->orderBy('code', 'ASC')
      ->get();

      $codsr = ac_coa::select('code', 'description')
      ->where('type', 'Expense')
      ->where('code','like', 'SR%')
      ->orderBy('code', 'ASC')
      ->get();


    $codes = ac_coa::select('code', 'description')
      ->where('type', 'Revenue')
      ->where('schedule', 'Revenue From Operations')
      ->orderBy('code', 'ASC')
      ->get();

    $types = DB::table('ims_itemtypes as a')
      ->join('ims_itemcodes as b', 'a.catgroup', '=', 'b.catgroup')
      ->where('b.id', '=', $id)
      ->select('a.type')
      ->get();


    $sunits = ims_itemunits::select('sunits')
      ->get()
      ->unique('sunits');

    $taxcode = ims_taxcodes::select('code')
      ->where('code', 'like', '%gst%')
      ->get()
      ->unique('code');


    $taxApplicable = ims_itemcodes::where('id', $id)
      ->select('tax_applicable')
      ->get();

      $lel2flag = ims_itemcodes::where('id', $id)->first()->lel2flag;

    $sunits1 = ims_itemunits::where('sunits', 'bags')
      ->select('sunits')
      ->get()
      ->unique('sunits');

    $ims_itemcodes = ims_itemcodes::findOrFail($id);
    return view(
      'content.masters.ItemMaster-add-edit',
      compact('ims_itemcodes', 'categoryGroups', 'sunits', 'sunits1', 'taxcode', 'types', 'taxApplicable', 'codec', 'codee', 'codes', 'categorytypes','lel2flag','codsr')
    );
  }


  public function activeinactive($id)
  {
      // Fetch the item from the database
      $ims_itemcode = DB::table('ims_itemcodes')->where('code', $id)->first();
  
      if ($ims_itemcode) {
          // Toggle the halt_flag value
          $newHaltFlag = $ims_itemcode->halt_flag == 1 ? 0 : 1;
  
          // Update the record in the database
          DB::table('ims_itemcodes')
              ->where('code', $id)
              ->update(['halt_flag' => $newHaltFlag]);
  
          // Prepare the data for API request
          $apiData = [
     
                  'client' => $ims_itemcode->client,
                  'code' => $ims_itemcode->code,
                  'db' => session()->get("db"),
              
          ];
          $apiData = json_encode($apiData);

          // Initialize cURL session
          $ch = curl_init();
      
          // Set cURL options
          curl_setopt($ch, CURLOPT_URL, 'https://secondary.sbl1972.in/secondarysales/itemflagupdate.php');
          curl_setopt($ch, CURLOPT_POST, true);
          curl_setopt($ch, CURLOPT_POSTFIELDS, $apiData); // Encode data in JSON format
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
      
          // Handle the API response
          if ($httpCode == 200 && !$curlError) {
              // Optional: Handle successful response if needed
              return redirect()
                  ->route('masters-ItemMaster')
                  ->with('success', 'Item status updated successfully!');
          } else {
              // Handle failed API response
              return redirect()
                  ->route('masters-ItemMaster')
                  ->with('error', 'Failed to update PO flag: ' . $curlError);
          }
      } else {
          // Handle case when item is not found
          return redirect()
              ->route('masters-ItemMaster')
              ->with('error', 'Item not found!');
      }
  }
  


  public function store(Request $request)
  {

    // Define validation rules
    $validatedData = $request->validate([
      'code' => 'required|string|max:255|unique:ims_itemcodes|regex:/^(?!\s)(?!.*\s{2,})(?!.*\s$)[a-zA-Z0-9]+$/',
      'description' => 'required|string|max:255|regex:/^(?!\s)(?!.*\s{2,})(?!.*\s$)[a-zA-Z0-9]+(?: [a-zA-Z0-9]+)*$/',
      'cat' => 'required|string|max:255',
      'type' => 'required|string|max:255',
      'iac' => 'required|string|max:255',
      'iusage' => 'required|string|max:255',
      'catgroup' => 'required|string|max:255',
      'pieces' => 'required|regex:/^[0-9]+$/',
'weight' => 'required|regex:/^(?!\s)(?!.*\s$)\d+(\.\d+)?$/',

'packetweight' => 'required|regex:/^(?!\s)(?!.*\s$)\d+(\.\d+)?$/',

      'sunits' => 'required|string|max:255',
      'saunits' => 'required|string|max:255',
      'cunits' => 'required|string|max:255',
      'source' => 'required|string|max:255',
       'ean' => 'required|regex:/^(?!\s)(?!.*\s$)[0-9]{13}$/|digits:13',
        'hsn' => 'required|regex:/^(?!\s)(?!.*\s$)[0-9]{6,10}$/|min:6|max:10', 
       'expca' => 'required_if:iusage,General Consumption',
      'sractd' => 'required_if:iusage,Produced or Sale|required_if:iusage,Rejected or Sale|required_if:iusage,Produced or Sale or Rejected|required_if:iusage,Sale',
      'cogsac' => 'required_if:iusage,Produced or Sale|required_if:iusage,Rejected or Sale|required_if:iusage,Produced or Sale or Rejected|required_if:iusage,Sale',
      'sac' => 'required_if:iusage,Produced or Sale|required_if:iusage,Rejected or Sale|required_if:iusage,Produced or Sale or Rejected|required_if:iusage,Sale',
  ], [
      'code.regex' => 'Only alphabets (A-Z), numbers (0-9) are allowed  and Consecutive,Leading,Trailing spaces not allowed',
    'description.regex' => 'Only alphabetic characters (A-Z, a-z), numbers (0-9), and a single space are allowed and Consecutive,Leading,Trailing spaces not allowed',
      'pieces.regex' => 'Only numbers (0-9) are allowed and Consecutive,Leading,Trailing spaces not allowed',
      'pieces.required' => 'The No of pieces field is required.',
      'catgroup.required' => 'The category group field is required.',
      'cat.required' => 'The category field is required.',
      'weight.regex' => 'Only numbers (0-9) and one decimal point are allowed  and Consecutive,Leading,Trailing spaces not allowed',
      'packetweight.regex' => 'Only numbers (0-9) and one decimal point are allowed  and Consecutive,Leading,Trailing spaces not allowed',
      'ean.regex' => 'EAN must be exactly 13 characters long and Only numbers (0-9) are allowed and Consecutive,Leading,Trailing spaces not allowed',
      'hsn.regex' => 'HSN must be at least 6 characters long and HSN must be no more than 10 characters long Only numbers (0-9) are allowed  and Consecutive,Leading,Trailing spaces not allowed',
'cogsac.required_if' => 'The COGS A/C field is required when usage is Sale.',
      'iac.required' => 'The Item A/C field is required.',
      'sac.required_if' => 'The Sales A/C field is required when usage is Sale.',
      'sractd.required_if' => 'The Sales Return A/C field is required when usage is Sale.',
      'expca.required_if' => 'The Expense A/C field is required when usage is General Consumption.',
  ]);
  
  
  



    // Create new item instance
    $nn = new ims_itemcodes();
    $nn->code = $validatedData['code'];
    $nn->description = $validatedData['description'];
    $nn->cat = $validatedData['cat'];
    $nn->catgroup = $validatedData['catgroup'];
    $nn->type = $validatedData['type'];

    $nn->sunits = $validatedData['sunits'];
    $nn->cunits = $validatedData['cunits'];
    $nn->source = $validatedData['source'];
    $nn->iusage = $validatedData['iusage'];
    $nn->pieces = $validatedData['pieces'];
    $nn->weight = $validatedData['weight'];
    $nn->packetweight = $validatedData['packetweight'];
 

    $nn->tax_applicable = $request->input('tax_applicable');
    $nn->sac = $request->input('sac');
    $nn->wpac = $request->input('expca');
    $nn->iac = $request->input('iac');
    $nn->cogsac = $request->input('cogsac');
    $nn->srac = $request->input('sractd');
    $nn->ean_no = $request->input('ean');
    $nn->hsn = $request->input('hsn');
    $nn->sales_units = $request->input('saunits');
    $nn->client = contactdetails::where('active_flag', 1)->pluck('company')->first();
    $nn->updated_by = session()->get("valid_user");

    $nn->save();

    return redirect()
      ->route('masters-ItemMaster')
      ->with('success', 'Item saved successfully!');
  }

  public function update(Request $request, $id)
  {
    $ims_itemcodes = ims_itemcodes::findOrFail($id);
    // Define validation rules
    $validatedData = $request->validate([
'code' => [
    'required',
    'string',
    'max:255',
    'regex:/^(?!\s)(?!.*\s{2,})(?!.*\s$)[a-zA-Z0-9]+$/',
    Rule::unique('ims_itemcodes')->ignore($ims_itemcodes->id)
],
    'description' => 'required|string|max:255|regex:/^(?!\s)(?!.*\s{2,})(?!.*\s$)[a-zA-Z0-9]+(?: [a-zA-Z0-9]+)*$/',
    'cat' => 'required|string|max:255',
    'type' => 'required|string|max:255',
    'iac' => 'required|string|max:255',
    'iusage' => 'required|string|max:255',
    'catgroup' => 'required|string|max:255',
    'pieces' => 'required|regex:/^[0-9]+$/',
'weight' => 'required|regex:/^(?!\s)(?!.*\s$)\d+(\.\d+)?$/',

'packetweight' => 'required|regex:/^(?!\s)(?!.*\s$)\d+(\.\d+)?$/',

    'sunits' => 'required|string|max:255',
    'saunits' => 'required|string|max:255',
    'cunits' => 'required|string|max:255',
    'source' => 'required|string|max:255',
'ean' => 'required|regex:/^(?!\s)(?!.*\s$)[0-9]{13}$/|digits:13',
'hsn' => 'required|regex:/^(?!\s)(?!.*\s$)[0-9]{6,10}$/|min:6|max:10',

  
      // Conditional validation
      'expca' => 'required_if:iusage,General Consumption',
      'sractd' => 'required_if:iusage,Produced or Sale|required_if:iusage,Rejected or Sale|required_if:iusage,Produced or Sale or Rejected|required_if:iusage,Sale',
      'cogsac' => 'required_if:iusage,Produced or Sale|required_if:iusage,Rejected or Sale|required_if:iusage,Produced or Sale or Rejected|required_if:iusage,Sale',
      'sac' => 'required_if:iusage,Produced or Sale|required_if:iusage,Rejected or Sale|required_if:iusage,Produced or Sale or Rejected|required_if:iusage,Sale',

    ],
    [
      'code.regex' => 'Only alphabets (A-Z), numbers (0-9) are allowed  and Consecutive,Leading,Trailing spaces not allowed',
      'description.regex' => 'Only alphabetic characters (A-Z, a-z), numbers (0-9), and a single space are allowed and Consecutive,Leading,Trailing spaces not allowed',
      'pieces.regex' => 'Only numbers (0-9) are allowed and Consecutive,Leading,Trailing spaces not allowed',
        'pieces.required' => 'The No of pieces field is required.',
        'catgroup.required' => 'The category group field is required.',
        'cat.required' => 'The category field is required.',
        'weight.regex' => 'Only numbers (0-9) and one decimal point are allowed  and Consecutive,Leading,Trailing spaces not allowed',
        'packetweight.regex' => 'Only numbers (0-9) and one decimal point are allowed  and Consecutive,Leading,Trailing spaces not allowed',
        'ean.regex' => 'EAN must be exactly 13 characters long and Only numbers (0-9) are allowed and Consecutive,Leading,Trailing spaces not allowed',
        'hsn.regex' => 'HSN must be at least 6 characters long and HSN must be no more than 10 characters long Only numbers (0-9) are allowed  and Consecutive,Leading,Trailing spaces not allowed',
        'cogsac.required_if' => 'The COGS A/C field is required when usage is Sale.',
        'iac.required' => 'The Item A/C field is required.',
        'sac.required_if' => 'The Sales A/C field is required when usage is Sale.',
        'sractd.required_if' => 'The Sales Return A/C field is required when usage is Sale.',
        'expca.required_if' => 'The Expense A/C field is required when usage is General Consumption.',
  ]);

    // Find the existing item


    // Update the item's properties
    $ims_itemcodes->code = $validatedData['code'];
    $ims_itemcodes->description = $validatedData['description'];
    $ims_itemcodes->cat = $validatedData['cat'];
    $ims_itemcodes->catgroup = $validatedData['catgroup'];
    $ims_itemcodes->type = $validatedData['type'];

    $ims_itemcodes->sunits = $validatedData['sunits'];
    $ims_itemcodes->cunits = $validatedData['cunits'];
    $ims_itemcodes->source = $validatedData['source'];
    $ims_itemcodes->iusage = $validatedData['iusage'];



    $ims_itemcodes->tax_applicable = $request->input('tax_applicable', $ims_itemcodes->tax_applicable);
    $ims_itemcodes->iac = $request->input('iac', $ims_itemcodes->iac);
    $ims_itemcodes->sac = $request->input('sac', $ims_itemcodes->sac);
    $ims_itemcodes->cogsac = $request->input('cogsac', $ims_itemcodes->cogsac);
    $ims_itemcodes->srac = $request->input('sractd', $ims_itemcodes->srac);
    $ims_itemcodes->ean_no = $request->input('ean', $ims_itemcodes->ean_no);
    $ims_itemcodes->hsn = $request->input('hsn', $ims_itemcodes->hsn);

    $ims_itemcodes->updated_by = session()->get("valid_user");

    // Save the updated item
    $ims_itemcodes->save();

    // Return response
    return redirect()
      ->route('masters-ItemMaster')
      ->with('success', 'Item updated successfully!');
  }

  public function destroy($id)
  {
    $ims_itemcodes = ims_itemcodes::findOrFail($id);
    $ims_itemcodes->delete();

    return redirect()
      ->route('masters-ItemMaster')
      ->with('success', 'Item deleted successfully!');
  }
}
