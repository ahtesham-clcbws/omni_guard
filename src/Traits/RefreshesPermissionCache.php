<?php

namespace OmniGuard\Traits;

use OmniGuard\PermissionRegistrar;

trait RefreshesPermissionCache
{
    public static function bootRefreshesPermissionCache()
    {
        static::saved(function () {
            app(\OmniGuard\PermissionRegistrar::class)->forgetCachedPermissions();
        });

        static::deleted(function () {
            app(\OmniGuard\PermissionRegistrar::class)->forgetCachedPermissions();
        });
    }
}
