<?php

namespace OmniGuard;

use Illuminate\Contracts\Auth\Access\Gate;
use Illuminate\Contracts\Auth\Access\Authorizable;
use Illuminate\Support\ServiceProvider;
use OmniGuard\Contracts\Permission as PermissionContract;
use OmniGuard\Contracts\Role as RoleContract;
use OmniGuard\Engine\HierarchyEngine;
use OmniGuard\Engine\PermissionWalker;
use OmniGuard\Engine\BitmaskRegistrar;
use OmniGuard\Engine\TenantManager;

class OmniGuardServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/omniguard.php', 'omniguard'
        );

        $this->app->singleton(\OmniGuard\PermissionRegistrar::class);
        $this->app->singleton(\OmniGuard\Engine\HierarchyEngine::class);
        $this->app->singleton(\OmniGuard\Engine\PermissionWalker::class);
        $this->app->singleton(\OmniGuard\Engine\BitmaskRegistrar::class);
        $this->app->singleton(\OmniGuard\Engine\TenantManager::class);
        $this->app->singleton('omniguard', \OmniGuard\OmniGuardManager::class);

        $this->app->bind(PermissionContract::class, function ($app) {
            return $app->make(config('omniguard.models.permission'));
        });

        $this->app->bind(RoleContract::class, function ($app) {
            return $app->make(config('omniguard.models.role'));
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/omniguard.php' => config_path('omniguard.php'),
            ], 'omniguard-config');

            $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        }

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'omniguard');
        $this->loadRoutesFrom(__DIR__.'/../routes/omniguard.php');

        $this->registerGateInterceptors();
    }

    /**
     * Register Gate interceptors for OmniGuard.
     */
    protected function registerGateInterceptors(): void
    {
        $gate = $this->app->make(Gate::class);
        $hierarchy = $this->app->make(HierarchyEngine::class);

        $gate->before(function (Authorizable $user, string $ability, array $args = []) use ($hierarchy) {
            // 1. Hierarchy & Panic Check
            $result = $hierarchy->check($user, $args[0] ?? null);
            if ($result !== null) {
                return $result;
            }

            // 2. Fall-through to standard OmniGuard logic (which we call via HasOmniGuard)
            // Note: registerPermissions() from PermissionRegistrar will be called individually 
            // after we ensure our hierarchy doesn't block it.
        });

        $this->app->make(PermissionRegistrar::class)->registerPermissions($gate);
    }
}
