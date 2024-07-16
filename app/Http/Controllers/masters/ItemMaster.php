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
    return view('content.masters.ItemMaster-add-edit', compact('categoryGroups', 'sunits', 'sunits1', 'taxcode', 'codec', 'codee', 'codes', 'categorytypes'));
  }



  public function saveimport(Request $request)
  {
    Excel::import(
      new ImportUser,
      $request->file('file')->store('files')
    );
    return redirect()->back();
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


    $sunits1 = ims_itemunits::where('sunits', 'bags')
      ->select('sunits')
      ->get()
      ->unique('sunits');

    $ims_itemcodes = ims_itemcodes::findOrFail($id);
    return view(
      'content.masters.ItemMaster-add-edit',
      compact('ims_itemcodes', 'categoryGroups', 'sunits', 'sunits1', 'taxcode', 'types', 'taxApplicable', 'codec', 'codee', 'codes', 'categorytypes')
    );
  }


  public function activeinactive($id)
  {

    $ims_itemcode = ims_itemcodes::find($id);

    if ($ims_itemcode) {

      $ims_itemcode->halt_flag = $ims_itemcode->halt_flag == 1 ? 0 : 1;


      $ims_itemcode->save();

      return redirect()
        ->route('masters-ItemMaster')
        ->with('success', 'Item saved successfully!');
    }
  }


  public function store(Request $request)
  {
    // Define validation rules
    $validatedData = $request->validate([
      'code' => 'required|string|max:255|unique:ims_itemcodes|regex:/^[a-zA-Z0-9]+$/',
      'description' => 'required|string|max:255|regex:/^[a-zA-Z0-9\s]+$/',
      'cat' => 'required|string|max:255',
      'catgroup' => 'required|string|max:255',
      'type' => 'required|string|max:255',

      'sunits' => 'required|string|max:255',
      'cunits' => 'required|string|max:255',
      'source' => 'required|string|max:255',
      'iusage' => 'required|string|max:255',
      'pieces' => 'required|string|max:255',
      'weight' => 'required|string|max:255',
      'packetweight' => 'required|string|max:255',


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


    $nn->sac = $request->input('tax_applicable');
    $nn->sac = $request->input('sac');
    $nn->wpac = $request->input('expca');
    $nn->iac = $request->input('iac');
    $nn->cogsac = $request->input('cogsac');
    $nn->srac = $request->input('sractd');
    $nn->ean_no = $request->input('ean');
    $nn->hsn = $request->input('hsn');

    // Save the item
    $nn->save();

    // Return response
    return redirect()
      ->route('masters-ItemMaster')
      ->with('success', 'Item saved successfully!');
  }

  public function update(Request $request, $id)
  {
    // Define validation rules
    $validatedData = $request->validate([
      'code' => 'required|string|max:255|regex:/^[a-zA-Z0-9]+$/',
      'description' => 'required|string|max:255|regex:/^[a-zA-Z0-9\s]+$/',
      'cat' => 'required|string|max:255',
      'catgroup' => 'required|string|max:255',
      'type' => 'required|string|max:255',

      'sunits' => 'required|string|max:255',
      'cunits' => 'required|string|max:255',
      'source' => 'required|string|max:255',
      'iusage' => 'required|string|max:255',


    ]);

    // Find the existing item
    $ims_itemcodes = ims_itemcodes::findOrFail($id);

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
