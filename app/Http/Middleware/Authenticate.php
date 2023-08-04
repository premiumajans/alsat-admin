<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Support\Facades\Route;

class Authenticate extends Middleware
{
    protected function redirectTo($request): ?string
    {
        if ($request->expectsJson()) {
            if ($request->is('api/*')) {
                return response()->json(['message' => 'Unauthorized'], 401);
            } else {
                return route('backend.loginForm');
            }
        }
        return null;
    }
}
