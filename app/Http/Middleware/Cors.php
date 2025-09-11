<?php

namespace App\Http\Middleware;

use Closure;

class Cors
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $host = $request->header('origin');
        $available_hosts = ['https://casquads.com', 'https://admin.casquads.com', 'http://127.0.0.1:8000', 'http://localhost:3000', 'https://casquad.com'];
        if(in_array($host, $available_hosts)) {
            return $next($request)
                ->header('Access-Control-Allow-Origin', '*')
                ->header('Access-Control-Allow-Methods', 'GET, POST')
                ->header('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, X-Token-Auth, Authorization');
        } else {
            return $next($request);
        }
    }
}
