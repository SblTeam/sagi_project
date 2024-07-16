<?php

namespace App\Http\Controllers\authentications;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Users\TblUser;
use Illuminate\Support\Facades\Hash;

class ForgotPasswordBasic extends Controller
{
  public function index()
  {
    return view('content.authentications.auth-forgot-password-basic');
  }
  public function changepass(Request $request)
  {
        $oldpass = $request->input('cpass');
        $username = $request->input('username');
        $pass = $request->input('pass');
        $cfpass = $request->input('cfpass');
        $tblUser = TblUser::where('username', $username)->first();
        if($tblUser && $pass){
          if($pass==$cfpass){
           TblUser::where('username', $username)->update(['password' => Hash::make($pass)]);
           return redirect()->route('auth-login-basic')->with('success', 'Password Updated successfully'); ;
          }else{
            return redirect()->route('auth-reset-password-basic')->with('error', 'Entered New password and confirm password should be same'); 
          }
        }
        else if($tblUser && password_verify($oldpass, $tblUser->password)){
          if($pass==$cfpass){
            TblUser::where('username', $username)->update(['password' => Hash::make($pass)]);
            return redirect()->route('auth-login-basic')->with('success', 'Password Updated successfully'); ;
           }else{
             return redirect()->route('auth-reset-password-basic')->with('error', 'Entered New password and confirm password should be same'); 
           }
        }else{
          return redirect()->route('auth-reset-password-basic')->with('error', 'Entered old password is wrong. Please check');
        }
       
  }
}
