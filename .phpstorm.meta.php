<?php

namespace PHPSTORM_META {

    override(\app(0), map([
        \OmniGuard\PermissionRegistrar::class => \OmniGuard\PermissionRegistrar::class,
        \OmniGuard\Engine\HierarchyEngine::class => \OmniGuard\Engine\HierarchyEngine::class,
        \OmniGuard\Engine\PermissionWalker::class => \OmniGuard\Engine\PermissionWalker::class,
        \OmniGuard\Engine\BitmaskRegistrar::class => \OmniGuard\Engine\BitmaskRegistrar::class,
        \OmniGuard\Engine\TenantManager::class => \OmniGuard\Engine\TenantManager::class,
        'omniguard' => \OmniGuard\OmniGuardManager::class,
    ]));

    override(\resolve(0), map([
        \OmniGuard\PermissionRegistrar::class => \OmniGuard\PermissionRegistrar::class,
        'omniguard' => \OmniGuard\OmniGuardManager::class,
    ]));

}
