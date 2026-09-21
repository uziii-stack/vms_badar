<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SnSea
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // $isOrg = session('user')->roles[0]->name === 'orgRep' || session('user')->roles[0]->name === 'admin' ? true : false;
        $user = User::with('roles', 'permissions')->where('uid', Auth::user()->uid)->first();
        $isOrg = $user->roles[0]->name == 'snseaAdmin' || $user->roles[0]->name == 'admin' ? true : false;
        if (!$isOrg) {
            return abort(403);
        }

        return $next($request);
    }
}
