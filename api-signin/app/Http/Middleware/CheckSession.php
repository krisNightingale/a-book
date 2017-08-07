<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Cache;

class CheckSession
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
        $token = $request->header('X-CSRF-TOKEN');
        $user = Cache::get($token);

        if (!$token){
            return response('Authorization Required', 401);
        }
        if (!$user){
            return response('Access Forbidden', 403);
        }
        return $next($request);
    }
}
