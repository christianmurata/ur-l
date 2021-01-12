<?php

namespace App\Http\Middleware;

use Closure, JWTAuth;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

class ApiAuthenticate extends BaseMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
        } catch (\Exception $error) {
            if($error instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException)
                return response()->json(['status' => 'Token is Invalid'], 401);

            if($error instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException)
                return response()->json(['status' => 'Token is Expired'], 401);

            return response()->json(['status' => 'Authorization Token not found.'], 401);
        }

        return $next($request);
    }
}
