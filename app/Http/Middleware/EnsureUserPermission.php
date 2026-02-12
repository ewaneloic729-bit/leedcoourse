<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureUserPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, ...$permissions)
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login');
        }

        $cachedPermissions = $request->session()->get('permissions');

        if (! is_array($cachedPermissions)) {
            $user->loadMissing('roleEntity.permissions');
            $cachedPermissions = $user->permissionNames();
            $request->session()->put('permissions', $cachedPermissions);
        }

        if ($user->isSuperadmin()) {
            return $next($request);
        }

        foreach ($permissions as $permission) {
            if (in_array($permission, $cachedPermissions, true)) {
                return $next($request);
            }
        }

        return redirect()->route('dashboard');
    }
}
