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
            
            return $permissionClass::query()
                ->whereNotNull('bit_index')
                ->get()
                ->mapWithKeys(function ($permission) {
                    return [$permission->name => 1 << $permission->bit_index];
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
