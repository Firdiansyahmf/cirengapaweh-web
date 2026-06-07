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
            $user = auth()->user();
            if (!$user || !$user->is_active) {
                auth()->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return redirect()->route('admin.login.form')->withErrors([
                    'email' => 'Akun Anda nonaktif.',
                ]);
            }

            if ($isLoginPage) {
                return redirect()->route('admin.dashboard');
            }
            return $next($request);
        }
        if ($isLoginPage) {
            return $next($request);
        }

        return redirect()->route('admin.login.form')->with('error', 'Sesi Anda telah berakhir atau Anda belum login.');
    }
}
