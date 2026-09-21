<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class Organization
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
        $isOrg = $user->roles[0]->name == 'orgRep' ||$user->roles[0]->name == 'admin' || $user->roles[0]->name == 'bxssUser'|| $user->roles[0]->name == 'printer'|| $user->roles[0]->name == 'sender'|| $user->roles[0]->name == 'authority'? true : false;
        if (!$isOrg) {
            return abort(403);
        }

        return $next($request);
    }
}
