<?php

namespace App\Http\Controllers\authentications;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Users\TblUser;
use App\Models\CommonUser;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Auth;

class LoginBasic extends Controller
{
  public function index()
  {
    return view('content.authentications.auth-login-basic');
  } 
  public function login(Request $request)
  {
    $username = $request->input('username');
        $password = $request->input('password');
        $tblUser = TblUser::where('username', $username)->first();
        $pass = TblUser::where('password', $password)->first();
        if($tblUser && $pass){}
        else if(!$tblUser || !password_verify($password, $tblUser->password)){
            return redirect()->route('auth-login-basic')->with('error', 'Invalid credentials from usersdb.');
        }
        $databaseName = $tblUser->dbase;
        Config::set('database.connections.dynamic.database', $databaseName);
        Config::set('database.default', 'dynamic');
        $commonUser = CommonUser::where('username', $username)->where('active', 1)->first();
        if (!$commonUser) {
            return redirect()->route('auth-login-basic')->with('error', 'Invalid Username from '.$databaseName.'.');
        }
        session()->put("db",$databaseName);
        session()->put("valid_user",$username);
        $request->session()->regenerate();
        return redirect()->route('dashboard-analytics');
  } 
  public function logout(Request $request)
  {
    $request->session()->flush();
    $request->session()->regenerate();
    return redirect('/auth/login-basic');
  }
}
