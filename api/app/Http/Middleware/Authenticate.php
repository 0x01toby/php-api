<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\AuthManager;
use Illuminate\Contracts\Auth\Factory as Auth;
use Illuminate\Support\Facades\Config;

class Authenticate
{
    /**
     * The authentication guard factory instance.
     *
     * @var \Illuminate\Contracts\Auth\Factory
     */
    protected $auth;

    /**
     * Create a new middleware instance.
     *
     * @param  \Illuminate\Contracts\Auth\Factory  $auth
     * @return void
     */
    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        /** @var AuthManager $auth */

        if (Config::get("is_from_mobile")) {
            $credentials['token'] = $request->input('custom_token');
        } else {
            $credentials['token'] = $request->cookie('custom_token');
        }

        // 鉴权
        if ($this->auth->guard($guard)->validate(['token' => $request->cookie('custom_token')]) ) {
            $this->auth->guard($guard)->setUser($this->auth->guard($guard)->lastAttempted);
        }

        if ($this->auth->guard($guard)->guest()) {
            return response('Unauthorized.', 401);
        }

        return $next($request);
    }
}
