<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AddTokenToHeader
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->cookie('token');

        if (!$token) {
            return response()->json([
                'message' => "Token xác thực thất bại."
            ], 401);
        }

        $request->headers->add(['Accept' => 'application/json']);
        $request->headers->add(['Authorization' => 'Bearer ' . $token]);

        return $next($request);
    }
}
