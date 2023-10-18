<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckUserRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, String $role): Response
    {
        \Log::info('Role: ' . auth()->user()->role);
        if(auth()->user()->role === $role) {
            return $next($request);
        }

        //abort(403);
        return redirect()->route('dashboard');
    }
}
