<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AllowFromFrontend
{
    public function handle(Request $request, Closure $next)
    {
        // Comma-separated list of allowed frontend domains from .env
        $allowed = env('ALLOWED_FRONTEND', '');
        $allowedDomains = array_map('trim', explode(',', $allowed));

        // Origin header (sent by browsers)
        $origin = $request->headers->get('origin');

        // Only allow requests **with an Origin header that matches the allowed domains**
        if ($origin && in_array($origin, $allowedDomains)) {
            return $next($request);
        }

        // All other cases → reject
        return response()->json([
            'success' => false,
            'message' => 'Unauthorized: Requests allowed only from your frontend domains'
        ], 401);
    }
}
