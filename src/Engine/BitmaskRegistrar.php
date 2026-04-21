<?php

namespace OmniGuard\Engine;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class BitmaskRegistrar
{
    protected string $cacheKey = 'omniguard.bitmask_registry';

    /**
     * Map all existing permissions to unique power-of-two indices.
     */
    public function getMap(): Collection
    {
        return Cache::rememberForever($this->cacheKey, function () {
            $permissionClass = config('omniguard.models.permission');
            
            return $permissionClass::orderBy('id')->get()->mapWithKeys(function ($permission, $index) {
                return [$permission->name => 1 << $index];
            });
        });
    }

    /**
     * Clear the bitmask registry.
     */
    public function clear(): void
    {
        Cache::forget($this->cacheKey);
    }
}
