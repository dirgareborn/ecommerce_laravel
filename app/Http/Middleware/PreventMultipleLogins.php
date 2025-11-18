<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class PreventMultipleLogins
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::guard('admin')->check()) {
            $user = Auth::guard('admin')->user();

            // Jika session_id di DB tidak sama dengan yang aktif → logout
            if ($user->session_id && $user->session_id !== Session::getId()) {
                Auth::logout();
                Session::flush();

                return redirect()->route('admin.login')->withErrors([
                    'email' => 'Akun Anda telah login di perangkat lain.',
                ]);
            }
        }
        return $next($request);
    }
}
