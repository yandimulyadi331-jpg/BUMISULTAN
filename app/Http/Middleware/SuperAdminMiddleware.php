<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class SuperAdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // If user is super admin, allow all permissions
        if (auth()->check() && auth()->user()->hasRole('super admin')) {
            // Grant all permissions to super admin
            Gate::before(function ($user, $ability) {
                return $user->hasRole('super admin') ? true : null;
            });
        }

        return $next($request);
    }
}
