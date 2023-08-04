<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    public function handle(Request $request, Closure $next, ...$guards)
    {
        $guards = empty($guards) ? [null] : $guards;
        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                if ($guard == 'web') {
                    return response()->json(['message' => 'unauthorized'], 401);
                    //return redirect(RouteServiceProvider::HOME);
                }if ($guard == 'api'){
                    return response()->json(['message' => 'Unauthorized'], 401);
                }
            }
        }
        return $next($request);
    }
}
