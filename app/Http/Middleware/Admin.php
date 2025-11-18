<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class Admin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string|null $module  (opsional, nama modul)
     * @param  string $access  (opsional, view/edit/full)
     */
    public function handle(Request $request, Closure $next, $module = null, $access = 'view'): Response
    {
        // 1️⃣ cek login
        if(!Auth::guard('admin')->check()){
            return redirect('/');
        }

        // 2️⃣ cek hak akses jika modul diberikan
        if ($module) {
            $admin = Auth::guard('admin')->user();
            $roles = $admin->roles ?? collect();

            $hasAccess = $roles->contains(function($role) use ($module, $access) {
                return in_array($module, $role->module) && $role->{$access . '_access'};
            });

            if (!$hasAccess) {
                abort(403, 'Anda tidak punya akses ke modul ini.');
            }
        }

        return $next($request);
    }
}
