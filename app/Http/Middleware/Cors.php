<?php

namespace App\Http\Middleware;

use Closure;

class Cors
{
    /*
    |--------------------------------------------------------------------------
    | Cors  —  返回同源策略
    |--------------------------------------------------------------------------
    |
    */

    public function handle($request, Closure $next)
    {
        if (config('app.debug')) {
            return $next($request)
            ->header('Access-Control-Allow-Credentials', 'true')
            ->header('Access-Control-Allow-Methods', 'OPTIONS, GET, POST, PUT, UPDATE, PATCH, DELETE')
            ->header('Access-Control-Allow-Headers', 'Content-Type, X-Session-Id, Cookie, multipart/form-data, application/json')
            ->header('Access-Control-Allow-Credentials', 'true')
            ->header('Access-Control-Allow-Origin', (isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : 'http://localhost:8080'));
        } else {
            return $next($request)
            ->header('Access-Control-Allow-Credentials', 'true')
            ->header('Access-Control-Allow-Methods', 'OPTIONS, GET, POST, PUT, UPDATE, PATCH, DELETE')
            ->header('Access-Control-Allow-Headers', 'Content-Type, X-Session-Id, Cookie, multipart/form-data, application/json')
            ->header('Access-Control-Allow-Credentials', 'true')
            ->header('Access-Control-Allow-Origin', '');
        }
    }
}
