<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->is('admin') || $request->is('admin/dashboard') || $request->is('admin/login') || $request->is('admin/logout')) {
            return $next($request);
        }

        if (session('user_role') !== 'admin') {
            return redirect()->route('admin.login')->with('error', 'Please sign in as admin to continue.');
        }

        return $next($request);
    }
}
