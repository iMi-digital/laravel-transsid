<?php

namespace iMi\LaravelTransSid;

use Closure;

class UrlSession
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
        // intentionally left empty - just for marking the requests
        return $next($request);
    }
}
