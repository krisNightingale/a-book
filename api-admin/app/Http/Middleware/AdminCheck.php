<?php

namespace App\Http\Middleware;

use App\Http\Controllers\SessionsController;
use App\User;
use Closure;

class AdminCheck
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
        $checkSession = SessionsController::isSessionActive($request);
        if ($checkSession->getStatusCode() != 200){
            return $checkSession;
        }

        $userId = SessionsController::isSessionActive($request)->getContent();
        $user = User::find($userId);

        if (!$user->is_admin){
            return response('Access denied', 400);
        }
        return $next($request);
    }
}
