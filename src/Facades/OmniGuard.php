<?php

namespace OmniGuard\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static bool hasTenant()
 * @method static int|string|null getTenantId()
 * @method static void setTenant(int|string|\Illuminate\Database\Eloquent\Model $tenant)
 * @method static bool panicMode()
 * 
 * @see \OmniGuard\OmniGuardManager
 */
class OmniGuard extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'omniguard';
    }
}
