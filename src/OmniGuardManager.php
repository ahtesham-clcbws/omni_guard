<?php

namespace OmniGuard;

use OmniGuard\Engine\TenantManager;
use OmniGuard\Engine\HierarchyEngine;

class OmniGuardManager
{
    public function __construct(
        protected TenantManager $tenant,
        protected HierarchyEngine $hierarchy
    ) {}

    public function setTenant(int|string|\Illuminate\Database\Eloquent\Model $tenant): void
    {
        $this->tenant->setTenant($tenant);
    }

    public function getTenantId(): int|string|null
    {
        return $this->tenant->getTenantId();
    }

    public function hasTenant(): bool
    {
        return $this->tenant->hasTenant();
    }

    public function panicMode(): bool
    {
        return config('omniguard.panic_mode', false);
    }
}
