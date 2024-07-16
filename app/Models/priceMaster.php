<?php

namespace App\Http\Controllers\masters;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ims_itemcodes;
use App\Models\oc_salesorder;
use App\Models\oc_loadingslip;
use App\Models\oc_cobi;
use App\Models\oc_pricemaster;

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

    $codes = oc_salesorder::select('code')
      ->distinct()
      ->pluck('code')
      ->toArray();

    $codeds = oc_cobi::select('code')
      ->where('dflag', 0)
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
      ->where('flag', 1)
      ->where('iusage', 'LIKE', '%Sale%')
      ->whereNotIn('code', $check_code_array)
      ->groupBy('cat')
      ->get();

    return view('content.masters.priceMaster-add-edit', compact('items'));
  }

  public function edit($id)
  {
    $oc_pricemaster = oc_pricemaster::findOrFail($id);
    $check_code = '';
    $date1 = date('d.m.Y');
    $date11 = date('Y-m-d', strtotime($date1));

    $codes = oc_salesorder::select('code')
      ->distinct()
      ->pluck('code')
      ->toArray();

    $codeds = oc_cobi::select('code')
      ->where('dflag', 0)
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

    return view('content.masters.priceMaster-add-edit', compact('items', 'oc_pricemaster', 'description', 'codep'));
  }

  public function store(Request $request)
{
    // Define validation rules
    $validatedData = $request->validate([
        'category' => 'required|array',
        'description' => 'required|array',
        'code' => 'required|array',
        'units' => 'required|array',
        'price' => 'required|array',
    ]);

    foreach ($validatedData['category'] as $index => $category) {
        // Check for non-empty description and category
        if (!empty($validatedData['description'][$index]) && !empty($category)) {
            // Create new item instance
            $nn = new oc_pricemaster();
            $nn->cat = $category;
            $nn->desc = $validatedData['description'][$index];
            $nn->code = $validatedData['code'][$index];
            $nn->units = $validatedData['units'][$index];
            $nn->price = $validatedData['price'][$index];

            $nn->save();
        }
    }

    return redirect()
        ->route('masters-PriceMaster')
        ->with('success', 'Item saved successfully!');
}



  public function update(Request $request, $id)
  {
    // Define validation rules
    $validatedData = $request->validate([
      'category' => 'required|array',
      'description' => 'required|array',
      'code' => 'required|array',
      'units' => 'required|array',
      'price' => 'required|array',
    ]);

    foreach ($validatedData['category'] as $index => $category) {

        // Create new item instance


        $nn = oc_pricemaster::findOrFail($id);

        $nn->cat = $category;
        $nn->desc = $validatedData['description'][$index];
        $nn->code = $validatedData['code'][$index];
        $nn->units = $validatedData['units'][$index];
        $nn->price = $validatedData['price'][$index];

        $nn->save();

    }

    return redirect()
      ->route('masters-PriceMaster')
      ->with('success', 'Item saved successfully!');
  }

  public function destroy($id)
  {
    $oc_pricemaster = oc_pricemaster::findOrFail($id);
    $oc_pricemaster->delete();

    return redirect()
      ->route('masters-PriceMaster')
      ->with('success', 'Item deleted successfully!');
  }
}
