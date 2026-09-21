<?php

namespace App\Http\Middleware;

use App\Models\ImageBlob;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RefreshSessionUser
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = User::with('roles', 'permissions')->find(Auth::id());

            if ($user) {
                $user->images = ImageBlob::where('uid', $user->uid)->first();
                $request->session()->put('user', $user);
            }
        }

        return $next($request);
    }
}
