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
            $dynamic_db=session()->get("db");
            $primarydb=session()->get("primarydb");
        Config::set('database.connections.dynamic.database', $dynamic_db);
        Config::set('database.connections.mysql2.database', $primarydb);
        Config::set('database.default', 'dynamic');
        return $next($request);
        }else{
            return redirect()->route("auth-login-basic");
        }
        
    }
}
