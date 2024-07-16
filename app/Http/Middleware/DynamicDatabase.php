<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Config;

class DynamicDatabase
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        // Determine database name dynamically from request (replace with your logic)
        $databaseName = $request->input('database_name');

        if ($databaseName) {
            Config::set('database.connections.dynamic.database', $databaseName);
            // Switch to the dynamic database connection
            Config::set('database.default', 'dynamic');
        }

        return $next($request);
    }
}
