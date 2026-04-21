<?php

namespace OmniGuard\Engine;

use Illuminate\Database\Eloquent\Model;

class TenantManager
{
    /**
     * The current active tenant ID.
     */
    protected int|string|null $tenantId = null;

    /**
     * Set the current active tenant.
     */
    public function setTenant(int|string|Model $tenant): void
    {
        $this->tenantId = $tenant instanceof Model ? $tenant->getKey() : $tenant;
    }

    /**
     * Get the current active tenant ID.
     */
    public function getTenantId(): int|string|null
    {
        return $this->tenantId;
    }

    /**
     * Determine if a tenant is currently active.
     */
    public function hasTenant(): bool
    {
        return !is_null($this->tenantId);
    }
}
