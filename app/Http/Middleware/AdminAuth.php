<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminAuth
{
    public function handle(Request $request, Closure $next): Response
    {
        $isLoginPage = $request->is('admin/login');
        $isAuthenticated = auth()->check();

        if ($isAuthenticated) {
            if ($isLoginPage) {
                return redirect()->route('admin.dashboard');
            }
            return $next($request);
        }
        if ($isLoginPage) {
            return $next($request);
        }

        return redirect('/')->with('error', 'Unauthorized access. Please login first.');
    }
}
