<?php

namespace OmniGuard\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PanicModeMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (config('omniguard.panic_mode', false)) {
            $user = $request->user();
            
            // Allow Super Admin even in panic mode
            if ($user && $this->isSuperAdmin($user)) {
                return $next($request);
            }

            abort(503, 'System is in Protected Mode (OmniGuard Panic Active). Non-Administrative access is temporarily suspended.');
        }

        return $next($request);
    }

    protected function isSuperAdmin($user): bool
    {
        $superAdminEmail = config('omniguard.super_admin.email');
        return $user && property_exists($user, 'email') && $user->email === $superAdminEmail;
    }
}
