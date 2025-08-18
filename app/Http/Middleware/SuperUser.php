<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SuperUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {

        $user = auth()->user();

        if ($user && $user->active == 1 && $user->role_id >= 1 && $user->role_id <= 4) {
            // User is active and has a valid role, allow access to the admin panel
            return $next($request);
        }

        return redirect('/admin/login');
    }
}
