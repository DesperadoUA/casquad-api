<?php
namespace App\Http\Middleware;
use Closure;

class Geo
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
        $geo = $request->has('geo') ? $request->query('geo') : config('geo.DEFAULT_GEO');
        $request->merge(['geo' => $geo]);
        return $next($request);
    }
}
