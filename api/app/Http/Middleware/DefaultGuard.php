<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Config;

class DefaultGuard
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
        Config::set('auth.defaults.guard', 'custom_jwt');
        return $next($request);
    }
}
