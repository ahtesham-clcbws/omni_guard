<?php

namespace OmniGuard\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use OmniGuard\Services\ImpersonationGuard;

class Impersonate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (ImpersonationGuard::isImpersonating()) {
            // Tag the request for UI or logging purposes
            $request->attributes->add(['omniguard_is_impersonating' => true]);
        }

        return $next($request);
    }
}
