<?php

namespace Sukohi\FoxTail\Middleware;

use Closure;

class FoxTailMiddleware
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
        \FoxTail::changeTails($request);
        return $next($request);
    }
}
