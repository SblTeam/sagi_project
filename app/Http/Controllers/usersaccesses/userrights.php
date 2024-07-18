<?php

namespace App\Http\Controllers\usersaccesses;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\commonUser;
use App\Models\Users\TblUser;
use Illuminate\Support\Facades\Hash;

class userrights extends Controller
{
public function index(){
    $userrights = commonUser::all();
    return view('content.usersaccesses.userrights', compact('userrights'));
  }
  public function add(){
    $urhts = commonUser::where("username",session()->get("valid_user"))->firstOrFail();
    return view('content.usersaccesses.userrights-add-edit', compact('urhts'));
  }
  public function store(Request $request){
    $request->validate([
    'username' => 'required|regex:/^[A-Za-z0-9][A-Za-z0-9&.\-, ]{0,48}[A-Za-z0-9.]$/',
    'password' => 'required',
    'phone' => 'required|digits:10',
    'email' => 'required|email',
    'company' => 'required',
  ]);
  $request['password']=Hash::make($request->password);
  $count=commonUser::where("username",$request->username)->count();
  echo $count;
  print_r($request);
commonUser::create([
  'username' => $request->username,
  'phone' => $request->phone,
  'company' => $request->company,
  'email' => $request->email,
  'view' => $request->company,
  'active' => 1,
  'authorize' => 1
]);
TblUser::create([
  'username' => $request->username,
  'phone' => $request->phone,
  'company' => $request->company,
  'email' => $request->email,
  'view' => $request->company,
  'active' => 1,
  'authorize' => 1
]);
}
  public function edit(){
    return view('content.usersaccesses.userrights-add-edit');
  }
  public function update(){
    return view('content.usersaccesses.userrights');
  }
  public function active(Request $request){
    $id=$request['id'];$status=$request['status'];
    $commonUser = commonUser::findOrFail($id);
    if($status==1){$act_alert='Successfully Activated ';}else{$act_alert='Successfully Inactivated ';}
    $commonUser->update(['active' => $status]); return redirect()->route('usersaccesses-userrights')->with('success',  $act_alert.$commonUser->username." .");
  }
}
