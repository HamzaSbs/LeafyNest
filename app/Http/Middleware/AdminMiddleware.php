<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->is('admin') || $request->is('admin/dashboard')) {
            return $next($request);
        }

        if (session('user_role') !== 'admin') {
            return redirect('/home')->with('error', 'You must be an admin to access this area.');
        }

        return $next($request);
    }
}
