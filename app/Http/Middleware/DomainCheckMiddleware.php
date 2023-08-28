<?php

namespace App\Http\Middleware;

use Closure;

class DomainCheckMiddleware
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
        Log::info('----------------- Start socketClients -----------------');
        Log::info($request->headers->get('origin'));
        Log::info('----------------- End socketClients -----------------');
        Log::info($request->headers);
        return $next($request);
    }
}
