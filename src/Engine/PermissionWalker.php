<?php

namespace OmniGuard\Engine;

use Illuminate\Support\Collection;

class PermissionWalker
{
    /**
     * Determine if a set of permissions authorizes a specific ability,
     * accounting for dot-notation recursive propagation.
     */
    public function authorize(Collection $permissions, string $ability): bool
    {
        // 1. Direct match
        if ($permissions->contains('name', $ability)) {
            return true;
        }

        // 2. Recursive Parent check
        $parts = explode('.', $ability);
        
        while (count($parts) > 1) {
            array_pop($parts);
            $parentAbility = implode('.', $parts);
            
            if ($permissions->contains('name', $parentAbility)) {
                return true;
            }
        }

        return false;
    }
}
