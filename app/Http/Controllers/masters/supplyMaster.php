<?php

namespace App\Http\Controllers\masters;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\contactdetails;
use App\Models\onboard_connections;
use App\Models\statecodes;
use App\Models\ims_itemcodes;

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
        'name' => 'required|max:48|regex:/^(?!.*\s{2})(?!^\s)(?!.*\s$)[A-Za-z0-9][A-Za-z0-9&.\-_ ]{0,48}[A-Za-z0-9.]$/',
        'company' => 'required|max:100|regex:/^(?!.*\s{2})(?!^\s)(?!.*\s$)[A-Za-z0-9][A-Za-z0-9&.\-_ ]{0,100}[A-Za-z0-9.]$/',
        'address' => "required|max:255|regex:/^(?!.*\s{2,})(?!.*\s$)(?!^\s).*[A-Za-z0-9.,\-&\/\\\\]+$/",
        'place' => "required|max:100|regex:/^(?!.*\s{2,})(?!.*\s$)(?!^\s).*[A-Za-z0-9.,\-&\/\\\\]+$/",
        'pan' => 'required|regex:/^[A-Za-z]{5}[0-9]{4}[A-Za-z]{1}$/',
        'email' => 'required|regex:/^[\w\._-]+@[a-zA-Z\d\._-]+\.(?:[a-zA-Z]{2,})$/',
        'phone' => 'required|digits:10',
        'state' => 'required|max:50',
        'holder_name' => 'required|max:100|regex:/^(?!.*\s{2})(?!^\s)(?!.*\s$)[A-Za-z.]+(?: [A-Za-z.]+)*$/',
        'account_no' => 'required|max:50|regex:/^[A-Za-z0-9]+$/',
        'bank_name' => 'required|max:100|regex:/^(?!.*\s{2})(?!^\s)(?!.*\s$)[A-Za-z0-9.]+(?: [A-Za-z0-9.]+)*$/',
        'branch_name' => 'required|max:50|regex:/^(?!.*\s{2})(?!^\s)(?!.*\s$)[A-Za-z0-9.]+(?: [A-Za-z0-9.]+)*$/',
        'IFSC' => 'required|size:11|regex:/^[A-Za-z0-9]{11}$/',
        'gstin' => 'required|size:15|regex:/^[A-Za-z0-9]{15}$/',
        'files_path.*' => 'nullable|file|max:5048',
        'files_pathlogo' => 'required|file|max:3280',
    ],[
      'name.regex' =>'Only alphabets (A-Z), numbers (0-9), special characters (.-_& ) are allowed and Consecutive,Leading,Trailing spaces not allowed',
      'company.regex' =>'Only alphabets (A-Z), numbers (0-9), )special characters (.-_& ) are allowed and Consecutive,Leading,Trailing spaces not allowed',
      'address.regex' =>'Only alphabets (A-Z), numbers (0-9), special characters (.-,\/& ) are allowed and Consecutive,Leading,Trailing spaces not allowed',
      'place.regex' =>'Only alphabets (A-Z), numbers (0-9), special characters (.-,\/& ) are allowed and Consecutive,Leading,Trailing spaces not allowed',
      'email.regex' =>'Please enter a valid email.Only alphabets (A-Z), numbers (0-9), special characters (.-_) are allowed',
      'pan.regex' =>'Please enter a valid pan number. Ex: ABCDE1234F',
      'holder_name.regex' =>'Only alphabets (A-Z), spaces and dots are allowed and Consecutive,Leading,Trailing spaces not allowed',
      'account_no.regex' =>'Only alphabets (A-Z),numbers (0-9) are allowed',
      'bank_name.regex' =>'Only alphabets (A-Z), numbers (0-9), spaces and dots are allowed and Consecutive,Leading,Trailing spaces not allowed',
      'branch_name.regex' =>'Only alphabets (A-Z), numbers (0-9), spaces and dots are allowed and Consecutive,Leading,Trailing spaces not allowed ',
      'IFSC.regex' =>'Only alphabets (A-Z), numbers (0-9) are allowed',
      'gstin.regex' =>'Only alphabets (A-Z), numbers (0-9) are allowed'
    ]);
    $count = ContactDetails::count();
    $count_m=onboard_connections::where('company', $request->company)->count();
    if($count>0){return redirect()->route('masters-SupplyMaster')->with('Fail', 'Max Profiles Access Allowed Only One.');}
    if($count_m>0){return redirect()->route('masters-SupplyMaster')->with('Fail', 'This Company Already Registered.');}
    $filePaths = [];
    if ($request->hasFile('files_path')) {
      foreach ($request->file('files_path') as $file) {
          $fileName = time() . '_' . $file->getClientOriginalName();
          $file->move(public_path('assets/img/Masters_uploaded'), $fileName);
          $filePaths[] = 'assets/img/Masters_uploaded/' . $fileName; // Assuming files are stored in the public/uploads directory
      }
  }
  $filePathslogo='';
  if ($request->hasFile('files_pathlogo')) {
    $fileName = time() . '_' . $request->file('files_pathlogo')->getClientOriginalName();
    $request->file('files_pathlogo')->move(public_path('assets/img/logo'), $fileName);
          $filePathslogo = 'assets/img/logo/'.$fileName;
  }

    ContactDetails::create(array_merge(
      $request->all(), 
      ['files_path' => implode(',', $filePaths),'active_flag' => 1,"logo" => $filePathslogo]
  ));
  onboard_connections::create([
    'type' => "onboard",
    'profile_name' => $request->name,
    'company' => $request->company,
    'db_name' => session()->get("db"),
    'primary_db' => session()->get("primarydb"),
    'secondary_db' => session()->get("secondarydb"),
    'place' => $request->place,
    'state' => $request->state,
    'phone' => $request->phone,
    'email' => $request->email,
    'active' => 1,
    'tbl_name' => "ContactDetails",
    'localip' => getHostByName(getHostName()),
    'publicip' => $_SERVER['SERVER_NAME']
]);
   return redirect()->route('masters-SupplyMaster')->with('success', 'Profile Created Successfully.');
    }

public function edit($id)
{   $statecodes = statecodes::pluck('state');
    $contactDetail = ContactDetails::findOrFail($id);
    $itemcount = ims_itemcodes::count();
    return view('content.masters.SupplyMaster-add-edit', compact('contactDetail','statecodes','itemcount'));
}

public function update(Request $request, $id)
{

    $request->validate([
        'name' => 'required|max:48|regex:/^(?!.*\s{2})(?!^\s)(?!.*\s$)[A-Za-z0-9][A-Za-z0-9&.\-_ ]{0,48}[A-Za-z0-9.]$/',
        'company' => 'required|max:100|regex:/^(?!.*\s{2})(?!^\s)(?!.*\s$)[A-Za-z0-9][A-Za-z0-9&.\-_ ]{0,100}[A-Za-z0-9.]$/',
        'address' => "required|max:255|regex:/^(?!.*\s{2,})(?!.*\s$)(?!^\s).*[A-Za-z0-9.,\-&\/\\\\]+$/",
        'place' => "required|max:100|regex:/^(?!.*\s{2,})(?!.*\s$)(?!^\s).*[A-Za-z0-9.,\-&\/\\\\]+$/",
        'pan' => 'required|regex:/^[A-Za-z]{5}[0-9]{4}[A-Za-z]{1}$/',
        'email' => 'required|regex:/^[\w\._-]+@[a-zA-Z\d\._-]+\.(?:[a-zA-Z]{2,})$/',
        'phone' => 'required|digits:10',
        'state' => 'required|max:255',
        'holder_name' => 'required|max:100|regex:/^(?!.*\s{2})(?!^\s)(?!.*\s$)[A-Za-z.]+(?: [A-Za-z.]+)*$/',
        'account_no' => 'required|max:50|regex:/^[A-Za-z0-9]+$/',
        'bank_name' => 'required|max:100|regex:/^(?!.*\s{2})(?!^\s)(?!.*\s$)[A-Za-z0-9.]+(?: [A-Za-z0-9.]+)*$/',
        'branch_name' => 'required|max:50|regex:/^(?!.*\s{2})(?!^\s)(?!.*\s$)[A-Za-z0-9.]+(?: [A-Za-z0-9.]+)*$/',
        'IFSC' => 'required|size:11|regex:/^[A-Za-z0-9]{11}$/',
        'gstin' => 'required|size:15|regex:/^[A-Za-z0-9]{15}$/',
        'files_path.*' => 'nullable|file|max:3280',
         // Adjust validation rules as necessary
    ],[
      'name.regex' =>'Only alphabets (A-Z), numbers (0-9), special characters (.-_& ) are allowed and Consecutive,Leading,Trailing spaces not allowed',
      'company.regex' =>'Only alphabets (A-Z), numbers (0-9), )special characters (.-_& ) are allowed and Consecutive,Leading,Trailing spaces not allowed',
      'address.regex' =>'Only alphabets (A-Z), numbers (0-9), special characters (.-,\/& ) are allowed and Consecutive,Leading,Trailing spaces not allowed',
      'place.regex' =>'Only alphabets (A-Z), numbers (0-9), special characters (.-,\/& ) are allowed and Consecutive,Leading,Trailing spaces not allowed',
      'email.regex' =>'Please enter a valid email.Only alphabets (A-Z), numbers (0-9), special characters (.-_) are allowed',
      'pan.regex' =>'Please enter a valid pan number. Ex: ABCDE1234F',
      'holder_name.regex' =>'Only alphabets (A-Z), spaces and dots are allowed and Consecutive,Leading,Trailing spaces not allowed',
      'account_no.regex' =>'Only alphabets (A-Z),numbers (0-9) are allowed',
      'bank_name.regex' =>'Only alphabets (A-Z), numbers (0-9), spaces and dots are allowed and Consecutive,Leading,Trailing spaces not allowed',
      'branch_name.regex' =>'Only alphabets (A-Z), numbers (0-9), spaces and dots are allowed and Consecutive,Leading,Trailing spaces not allowed ',
      'IFSC.regex' =>'Only alphabets (A-Z), numbers (0-9) are allowed',
      'gstin.regex' =>'Only alphabets (A-Z), numbers (0-9) are allowed'
      ]);
    $contactDetail = ContactDetails::findOrFail($id);
    $onboard_connections = onboard_connections::where('profile_name', $contactDetail->name)->firstOrFail();
    $count_m=onboard_connections::where('id',"!=", $onboard_connections->id)->where('company', $request->company)->count();
    if($count_m>0){return redirect()->route('masters-SupplyMaster')->with('Fail', 'This Company Already Registered.');}
    
    $filePaths = [];
    $filePaths= array_merge($filePaths, $request->dummypath);
    if ($request->hasFile('files_path')) {
        foreach ($request->file('files_path') as $file) {
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('assets/img/Masters_uploaded'), $fileName);
            $filePaths[] = 'assets/img/Masters_uploaded/' . $fileName; // Assuming files are stored in the public/uploads directory
         }
    }
    $filePathslogo='';
    if ($request->hasFile('files_pathlogo')) {
      $fileName = time() . '_' . $request->file('files_pathlogo')->getClientOriginalName();
      $request->file('files_pathlogo')->move(public_path('assets/img/logo'), $fileName);
            $filePathslogo = 'assets/img/logo/'.$fileName;
    }else{
      $filePathslogo=$request->dummypathlogo;
    }
    $contactDetail->update(array_merge(
      $request->all(), 
      ['files_path' => implode(',', $filePaths),"logo" => $filePathslogo]
  ));
  $onboard_connections->update([
    'type' => "onboard",
    'profile_name' => $request->name,
    'company' => $request->company,
    'db_name' => session()->get("db"),
    'primary_db' => session()->get("primarydb"),
    'secondary_db' => session()->get("secondarydb"),
    'tbl_name' => "ContactDetails",
    'place' => $request->place,
    'state' => $request->state,
    'phone' => $request->phone,
    'email' => $request->email,
    'active' => 1,
    'updated_at' => now(),
    'localip' => getHostByName(getHostName()),
    'publicip' => $_SERVER['SERVER_NAME']
]);
//file_get_contents('https://api.ipify.org')
$contact1 = ContactDetails::where('id',$id)->where('auth_flag2', '1')->count();
if($contact1>0){
  $ch = curl_init();
  $url = 'https://secondary.sbl1972.in/secondarysales/updated_at_sagi.php';
  $data = ["bank_name" => $request->bank_name ,"branch_name" => $request->branch_name ,"IFSC" => $request->IFSC ,"holder_name" => $request->holder_name ,"account_no" => $request->account_no , "company" => $request->company, "email" => $request->email,"phone" => $request->phone,"db"=>session()->get("secondarydb")];
  $jsonData = json_encode($data);
  curl_setopt_array($ch, [
      CURLOPT_URL => $url,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_POST => true,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_SSL_VERIFYPEER => false,
      CURLOPT_POSTFIELDS => $jsonData,
      CURLOPT_HTTPHEADER => [
          'Content-Type: application/json',
          'Content-Length: ' . strlen($jsonData)
      ]
  ]);
  $response = json_decode(curl_exec($ch));
  curl_close($ch);
  //echo curl_exec($ch);
}
return redirect()->route('masters-SupplyMaster')->with('success',' Profile  Updated Successfully.');
}


public function destroy($id)
{
    $contactDetail = ContactDetails::findOrFail($id);
    $onboard_connections = onboard_connections::where("profile_name",$contactDetail->name);
    $contactDetail->delete();
    $onboard_connections->delete();

    return redirect()->route('masters-SupplyMaster')->with('success', ' Profile Deleted Successfully.');
}

public function activate($id,$status){
  $contactDetail = ContactDetails::findOrFail($id);
  if($status==1){$act_alert='Profile Successfully Activated ';}else{$act_alert='Profile Successfully Inactivated ';}
  if($contactDetail->auth_flag2==0){$contactDetail->update(['active_flag' => $status]); return redirect()->route('masters-SupplyMaster')->with('success',  $act_alert." .");}
  else{return redirect()->route('masters-SupplyMaster')->with('Fail', 'Md Level Autharization Not Done For This Profile');}
}
}
