<?php

namespace App\Http\Controllers\masters;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\contactdetails;
use App\Models\onboard_connections;
use App\Models\statecodes;

class SupplyMaster extends Controller
{
  public function index()
  {
    $contactDetail = ContactDetails::all();
    return view('content.masters.SupplyMaster', compact('contactDetail'));
  }
  public function add()
  {
    $statecodes = statecodes::pluck('state');
    return view('content.masters.SupplyMaster-add-edit',compact('statecodes'));
  }

  public function store(Request $request)
    {
      $request->validate([
        'name' => 'required|regex:/^[A-Za-z0-9][A-Za-z0-9&.\-, ]{0,48}[A-Za-z0-9.]$/',
        'company' => 'required|regex:/^[A-Za-z0-9][A-Za-z0-9&.\-, ]{0,48}[A-Za-z0-9.]$/',
        'address' => "required|max:255|regex:/^[A-Za-z0-9\s.\-,'&]+$/",
        'place' => "required|max:100|regex:/^[A-Za-z0-9\s.\-,'&]+$/",
        'pan' => 'required|regex:/^[A-Za-z]{5}[0-9]{4}[A-Za-z]{1}$/',
        'email' => 'required|email',
        'phone' => 'required|digits:10',
        'state' => 'required|max:50',
        'gstin' => 'required|size:15|regex:/^[A-Za-z0-9]{15}$/',
        'files_path.*' => 'nullable|file|max:5048',
    ],[
      'name.regex' =>'Please enter a valid address. Only alphabets (A-Z), numbers (0-9), commas (,), ampersands (&), and hyphens (-) are allowed',
      'company.regex' =>'Please enter a valid address. Only alphabets (A-Z), numbers (0-9), commas (,), ampersands (&), and hyphens (-) are allowed'
    ]);
    $count = ContactDetails::count();
    if($count>0){return redirect()->route('masters-SupplyMaster')->with('Fail', 'Max profiles access allowed only one.');}
    $filePaths = [];
    if ($request->hasFile('files_path')) {
      foreach ($request->file('files_path') as $file) {
          $fileName = time() . '_' . $file->getClientOriginalName();
          $file->move(public_path('assets/img/Masters_uploaded'), $fileName);
          $filePaths[] = '/assets/img/Masters_uploaded/' . $fileName; // Assuming files are stored in the public/uploads directory
      }
  }

    ContactDetails::create(array_merge(
      $request->all(), 
      ['files_path' => implode(',', $filePaths),'active_flag' => 1]
  ));
  onboard_connections::create([
    'type' => "onboard",
    'profile_name' => $request->name,
    'company' => $request->company,
    'db_name' => session()->get("db"),
    'place' => $request->place,
    'state' => $request->state,
    'phone' => $request->phone,
    'email' => $request->email,
    'active' => 1,
    'tbl_name' => "ContactDetails",
    'localip' => $request->server('SERVER_ADDR'),
    'publicip' => $request->ip()
]);
   return redirect()->route('masters-SupplyMaster')->with('success', $request->name.' data saved successfully.');
    }

public function edit($id)
{   $statecodes = statecodes::pluck('state');
    $contactDetail = ContactDetails::findOrFail($id);
    return view('content.masters.SupplyMaster-add-edit', compact('contactDetail','statecodes'));
}

public function update(Request $request, $id)
{

    $request->validate([
        'name' => 'required|regex:/^[A-Za-z0-9][A-Za-z0-9&.\-, ]{0,48}[A-Za-z0-9.]$/',
        'company' => 'required|regex:/^[A-Za-z0-9][A-Za-z0-9&.\-, ]{0,48}[A-Za-z0-9.]$/',
        'address' => "required|max:255|regex:/^[A-Za-z0-9\s.\-,'&]+$/",
        'place' => "required|max:100|regex:/^[A-Za-z0-9\s.\-,'&]+$/",
        'pan' => 'required|regex:/^[A-Za-z]{5}[0-9]{4}[A-Za-z]{1}$/',
        'email' => 'required|email',
        'phone' => 'required|digits:10',
        'state' => 'required|max:255',
        'gstin' => 'required|size:15|regex:/^[A-Za-z0-9]{15}$/',
        'files_path.*' => 'nullable|file|max:5048', // Adjust validation rules as necessary
    ],[
      'name.regex' =>'Please enter a valid address. Only alphabets (A-Z), numbers (0-9), commas (,), ampersands (&), and hyphens (-) are allowed',
      'company.regex' =>'Please enter a valid address. Only alphabets (A-Z), numbers (0-9), commas (,), ampersands (&), and hyphens (-) are allowed'
    ]);
    $contactDetail = ContactDetails::findOrFail($id);
    $onboard_connections = onboard_connections::where('profile_name', $contactDetail->name)->firstOrFail();
    
    $filePaths = [];
    $filePaths= array_merge($filePaths, $request->dummypath);
    if ($request->hasFile('files_path')) {
        foreach ($request->file('files_path') as $file) {
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('assets/img/Masters_uploaded'), $fileName);
            $filePaths[] = '/assets/img/Masters_uploaded/' . $fileName; // Assuming files are stored in the public/uploads directory
         }
    }
    $contactDetail->update(array_merge(
      $request->all(), 
      ['files_path' => implode(',', $filePaths)]
  ));
  $onboard_connections->update([
    'type' => "onboard",
    'profile_name' => $request->name,
    'company' => $request->company,
    'db_name' => session()->get("db"),
    'tbl_name' => "ContactDetails",
    'place' => $request->place,
    'state' => $request->state,
    'phone' => $request->phone,
    'email' => $request->email,
    'active' => 1,
    'localip' => $request->server('SERVER_ADDR'),
    'publicip' => $request->ip()
]);
return redirect()->route('masters-SupplyMaster')->with('success', $contactDetail->name.' data updated successfully.');
}


public function destroy($id)
{
    $contactDetail = ContactDetails::findOrFail($id);
    $onboard_connections = onboard_connections::where("profile_name",$contactDetail->name);
    $contactDetail->delete();
    $onboard_connections->delete();

    return redirect()->route('masters-SupplyMaster')->with('success', $contactDetail->name.' data deleted successfully.');
}

public function activate($id,$status){
  $contactDetail = ContactDetails::findOrFail($id);
  if($status==1){$act_alert='Successfully Activated ';}else{$act_alert='Successfully Inactivated ';}
  if($contactDetail->auth_flag2==0){$contactDetail->update(['active_flag' => $status]); return redirect()->route('masters-SupplyMaster')->with('success',  $act_alert.$contactDetail->name." .");}
  else{return redirect()->route('masters-SupplyMaster')->with('Fail', 'Md level autharization not done for '.$contactDetail->name." .");}
}
}
