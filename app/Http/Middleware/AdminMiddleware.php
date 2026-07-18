<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->is('admin/login') || $request->is('admin/logout')) {
            return $next($request);
        }

        if (!Auth::check() || !Auth::user()->isAdmin()) {
            if (!$request->expectsJson()) {
                return redirect()->route('admin.login')
                    ->with('error', 'Please sign in as admin to continue.');
            }
            abort(403, 'Admin authentication required.');
        }

        return $next($request);
    }
}
