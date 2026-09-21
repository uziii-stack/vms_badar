<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AllowIframeMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // 1. Remove the restrictive X-Frame-Options header
        if (method_exists($response, 'header')) {
            $response->headers->remove('X-Frame-Options');

            // 2. Add the CSP rule to specifically whitelist your target domains
            $response->header('Content-Security-Policy', "frame-ancestors 'self' https://ieeepfair.com https://ieeepfair.com;");
        }

        return $response;
    }
}
