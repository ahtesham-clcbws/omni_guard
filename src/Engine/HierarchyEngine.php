<?php

namespace OmniGuard\Engine;

use Illuminate\Contracts\Auth\Access\Authorizable;
use OmniGuard\Contracts\Role;

class HierarchyEngine
{
    /**
     * Determine if the user has sufficient rank to perform an action on a target.
     * 
     * Higher rank = lower sort_order value.
     */
    public function check(Authorizable $user, $target = null): ?bool
    {
        if (config('omniguard.panic_mode')) {
            return $this->isSuperAdmin($user) ? true : false;
        }

        if (!$target || !method_exists($user, 'getTopRole')) {
            return null;
        }

        $userRole = $user->getTopRole();
        
        if (!$userRole) {
            return null;
        }

        // If target is another user/authorizable
        if ($target instanceof Authorizable && method_exists($target, 'getTopRole')) {
            $targetRole = $target->getTopRole();
            
            if ($targetRole && !$userRole->hasHigherRankThan($targetRole)) {
                // Cannot act on equal or higher rank
                return false;
            }
        }

        return null;
    }

    protected function isSuperAdmin(Authorizable $user): bool
    {
        $superAdminEmail = config('omniguard.super_admin.email');
        if ($superAdminEmail && method_exists($user, 'getEmail')) {
            return $user->getEmail() === $superAdminEmail;
        }

        return false;
    }
}
