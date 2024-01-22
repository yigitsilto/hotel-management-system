<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class UserMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if the user is authenticated and has the 'ADMIN' type
        if (Auth::check() && Auth::user()->role == 'USER') {
            return $next($request);
        }


        // Redirect to the login page or show an unauthorized page
        return redirect('/')->with('error', 'You do not have permission to access this page.');
    }
}
