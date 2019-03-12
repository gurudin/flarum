<?php

namespace App\Http\Middleware;

use Closure;

class AuthApi
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
        /**
         * Check app id.
         */
        if (config('api.token.web') != $request->header('app-id')) {
            return response()->json('403 Forbidden Error!', 403);
        }

        return $next($request);
    }
}
