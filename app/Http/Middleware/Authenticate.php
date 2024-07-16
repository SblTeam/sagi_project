<?php

namespace App\Http\Middleware;
use Closure;
use Illuminate\Support\Facades\Config;
use Illuminate\Http\Request;

class Authenticate
{
    
    public function handle(Request $request, Closure $next)
    {   
        if(session()->has("db")){
        Config::set('database.connections.dynamic.database', session()->get("db"));
        Config::set('database.default', 'dynamic');
        return $next($request);
        }else{
            return redirect()->route("auth-login-basic");
        }
        
    }
}
