<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AttandeeUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = User::with('roles', 'permissions')->where('uid', Auth::user()->uid)->first();
        $isAttandeeUser = $user->roles[0]->name == 'batchUser' || $user->roles[0]->name == 'attandeeUser' || $user->roles[0]->name == 'admin' || $user->roles[0]->name == 'bxssUser'  || $user->roles[0]->name == 'depo' || $user->roles[0]->name == 'ncc' ? true : false;
        if (!$isAttandeeUser) {
            return abort(403);
        }
        return $next($request);
    }
}
