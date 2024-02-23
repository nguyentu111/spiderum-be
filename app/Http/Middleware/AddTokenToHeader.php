<?php

namespace App\Http\Middleware;

use Closure;
use Cookie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
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
        $encryptedToken = Crypt::decrypt(Cookie::get('token'), false);
        $token = $this->encryptedStringToToken($encryptedToken);

        if (!$token) {
            return response()->json([
                'message' => "Token xác thực thất bại."
            ], 401);
        }

        $request->headers->add(['Accept' => 'application/json']);
        $request->headers->add(['Authorization' => 'Bearer ' .  $token]);

        return $next($request);
    }

    private function encryptedStringToToken(string $encryptedString): string
    {
        $split = explode('|', $encryptedString);
        unset($split[0]);

        return join('|', $split);
    }
}
