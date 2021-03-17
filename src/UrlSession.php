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
        ini_set('session.use_trans_sid', 1);
        ini_set('session.use_cookies', 0);
        ini_set('session.use_only_cookies', 0);
        return $next($request);
    }
}
