<?php

namespace App\Http\Middleware;

use Closure;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

class JWTAuthenticate extends BaseMiddleware
{
    public function handle($request, Closure $next)
    {
        if ($request->cookie(\Config::get('constants.cookieName'))) {
            $request->headers->set('Authorization', 'Bearer ' . $request->cookie(\Config::get('constants.cookieName')));
        }
        return $next($request);

    }
}
