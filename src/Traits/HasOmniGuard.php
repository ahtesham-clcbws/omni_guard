<?php

namespace OmniGuard\Traits;

use OmniGuard\Contracts\Role;

trait HasOmniGuard
{
    use HasRoles;

    /**
     * Get the highest ranking role for this user.
     * Higher rank = lower sort_order.
     */
    public function getTopRole(): ?Role
    {
        return $this->roles()->orderBy('sort_order', 'asc')->first();
    }

    /**
     * Get the user's email for SuperAdmin verification.
     */
    public function getEmail(): string
    {
        return $this->email;
    }
}
