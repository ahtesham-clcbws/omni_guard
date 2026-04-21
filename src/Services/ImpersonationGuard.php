<?php

namespace OmniGuard\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use OmniGuard\Audit\ImpersonationLog;

class ImpersonationGuard
{
    const SESSION_KEY = 'omniguard_impersonation_id';

    /**
     * Start impersonating a user.
     */
    public static function start(int|string $userId, ?string $reason = null): bool
    {
        if (self::isImpersonating()) {
            return false;
        }

        $impersonator = Auth::user();
        if (!$impersonator) {
            return false;
        }

        // Create audit log
        ImpersonationLog::create([
            'impersonator_id' => $impersonator->getAuthIdentifier(),
            'impersonated_id' => $userId,
            'started_at' => now(),
            'reason' => $reason,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        // Store original ID in session
        Session::put(self::SESSION_KEY, $impersonator->getAuthIdentifier());

        // Switch context
        Auth::loginUsingId($userId);

        return true;
    }

    /**
     * Stop the current impersonation session.
     */
    public static function stop(): bool
    {
        if (!self::isImpersonating()) {
            return false;
        }

        $impersonatedId = Auth::id();
        $impersonatorId = Session::get(self::SESSION_KEY);

        // Close audit log
        ImpersonationLog::where('impersonator_id', $impersonatorId)
            ->where('impersonated_id', $impersonatedId)
            ->whereNull('ended_at')
            ->update(['ended_at' => now()]);

        // Clear session
        Session::forget(self::SESSION_KEY);

        // Switch back
        Auth::loginUsingId($impersonatorId);

        return true;
    }

    /**
     * Check if the current session is an impersonation.
     */
    public static function isImpersonating(): bool
    {
        return Session::has(self::SESSION_KEY);
    }
}
