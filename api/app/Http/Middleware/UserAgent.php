<?php
/**
 * Created by PhpStorm.
 * User: zhangtao
 * Date: 19-4-10
 * Time: 下午7:59
 */

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;

class UserAgent
{
    public function handle(Request $request, Closure $next)
    {

        $user_agent = strtolower($request->header('user-agent', ''));
        if (Str::contains($user_agent, ['iphone', 'ipod']) || Str::contains($user_agent, 'android')) {
            Config::set('is_from_mobile', true);
        }

        if (Str::contains($user_agent, 'micromessenger')) {
            Config::set('is_from_mobile', true);
        }

        return $next($request);

    }
}
