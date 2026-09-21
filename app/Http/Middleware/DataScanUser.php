<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class DataScanUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = User::with('roles', 'permissions')->where('uid', Auth::user()->uid)->first();
        $isAuthorize = $user->roles[0]->name == 'admin' || $user->roles[0]->name == 'dataScan'? true : false;
        if (!$isAuthorize) {
            return abort(403);
        }
        return $next($request);
    }
}
