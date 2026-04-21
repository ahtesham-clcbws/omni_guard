<?php

namespace OmniGuard;

use Composer\InstalledVersions;
use Illuminate\Contracts\Auth\Access\Gate;
use Illuminate\Contracts\Auth\Access\Authorizable;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\Console\AboutCommand;
use Illuminate\Routing\Route;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\Compilers\BladeCompiler;
use OmniGuard\Contracts\Permission as PermissionContract;
use OmniGuard\Contracts\Role as RoleContract;
use OmniGuard\Engine\HierarchyEngine;
use OmniGuard\Engine\PermissionWalker;
use OmniGuard\Engine\BitmaskRegistrar;
use OmniGuard\Engine\TenantManager;
use OmniGuard\Services\ImpersonationGuard;
use OmniGuard\Http\Middleware\Impersonate;

class OmniGuardServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/omniguard.php', 'omniguard');

        $this->app->singleton(\OmniGuard\PermissionRegistrar::class);
        $this->app->singleton(\OmniGuard\Engine\HierarchyEngine::class);
        $this->app->singleton(\OmniGuard\Engine\PermissionWalker::class);
        $this->app->singleton(\OmniGuard\Engine\BitmaskRegistrar::class);
        $this->app->singleton(\OmniGuard\Engine\TenantManager::class);
        $this->app->singleton(\OmniGuard\Services\ImpersonationGuard::class);
        $this->app->singleton('omniguard', \OmniGuard\OmniGuardManager::class);

        $this->app->bind(PermissionContract::class, fn ($app) => $app->make($app->config['omniguard.models.permission']));
        $this->app->bind(RoleContract::class, fn ($app) => $app->make($app->config['omniguard.models.role']));

        $this->callAfterResolving('blade.compiler', fn (BladeCompiler $bladeCompiler) => $this->registerBladeExtensions($bladeCompiler));
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->offerPublishing();

        $this->registerMacroHelpers();

        $this->registerCommands();

        $this->registerOctaneListener();

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'omniguard');
        $this->loadRoutesFrom(__DIR__.'/../routes/omniguard.php');

        $this->app['router']->aliasMiddleware('omniguard.impersonate', Impersonate::class);

        $this->callAfterResolving(Gate::class, function (Gate $gate, Application $app) {
            /** @var PermissionRegistrar $permissionLoader */
            $permissionLoader = $app->get(PermissionRegistrar::class);
            $permissionLoader->clearPermissionsCollection();
            $permissionLoader->registerPermissions($gate);
        });

        $this->registerGateInterceptors();

        $this->registerAbout();
    }

    protected function offerPublishing(): void
    {
        if (! $this->app->runningInConsole()) {
            return;
        }

        $this->publishes([
            __DIR__.'/../config/omniguard.php' => config_path('omniguard.php'),
        ], 'omniguard-config');

        $this->publishes([
            __DIR__.'/../database/migrations/create_permission_tables.php.stub' => $this->getMigrationFileName('create_permission_tables.php'),
        ], 'omniguard-migrations');
    }

    protected function registerCommands(): void
    {
        $this->commands([
            Commands\CacheReset::class,
        ]);

        if (! $this->app->runningInConsole()) {
            return;
        }

        $this->commands([
            Commands\CreateRole::class,
            Commands\CreatePermission::class,
            Commands\Show::class,
            Commands\UpgradeForTeams::class,
            Commands\AssignRole::class,
            Commands\AuditIntegrationCommand::class,
        ]);
    }

    protected function registerOctaneListener(): void
    {
        if ($this->app->runningInConsole() || ! $this->app['config']->get('octane.listeners')) {
            return;
        }

        $dispatcher = $this->app[Dispatcher::class];

        if (class_exists('Laravel\Octane\Contracts\OperationTerminated')) {
            $dispatcher->listen(function ($event) {
                $event->sandbox->make(PermissionRegistrar::class)->setPermissionsTeamId(null);
            });

            $dispatcher->listen(function ($event) {
                $event->sandbox->make(PermissionRegistrar::class)->clearPermissionsCollection();
            });
        }
    }

    protected function registerGateInterceptors(): void
    {
        $gate = $this->app->make(Gate::class);
        $hierarchy = $this->app->make(HierarchyEngine::class);

        $gate->before(function (Authorizable $user, string $ability, array $args = []) use ($hierarchy) {
            $result = $hierarchy->check($user, $args[0] ?? null);
            if ($result !== null) {
                return $result;
            }
        });

        $this->app->make(PermissionRegistrar::class)->registerPermissions($gate);
    }

    public static function bladeMethodWrapper($method, $role, $guard = null): bool
    {
        return auth($guard)->check() && auth($guard)->user()->{$method}($role);
    }

    protected function registerBladeExtensions(BladeCompiler $bladeCompiler): void
    {
        $bladeMethodWrapper = '\\OmniGuard\\OmniGuardServiceProvider::bladeMethodWrapper';

        $bladeCompiler->if('omniguard', fn () => $bladeMethodWrapper('checkPermissionTo', ...func_get_args()));
        $bladeCompiler->if('haspermission', fn () => $bladeMethodWrapper('checkPermissionTo', ...func_get_args()));
        $bladeCompiler->if('omnirole', fn () => $bladeMethodWrapper('hasRole', ...func_get_args()));
        $bladeCompiler->if('role', fn () => $bladeMethodWrapper('hasRole', ...func_get_args()));
        $bladeCompiler->if('hasrole', fn () => $bladeMethodWrapper('hasRole', ...func_get_args()));
        $bladeCompiler->if('hasanyrole', fn () => $bladeMethodWrapper('hasAnyRole', ...func_get_args()));
        $bladeCompiler->if('hasallroles', fn () => $bladeMethodWrapper('hasAllRoles', ...func_get_args()));
        $bladeCompiler->directive('endomniguard', fn () => '<?php endif; ?>');
        $bladeCompiler->directive('endomnirole', fn () => '<?php endif; ?>');
    }

    protected function registerMacroHelpers(): void
    {
        if (! method_exists(Route::class, 'macro')) {
            return;
        }

        Route::macro('role', function ($roles = []) {
            $roles = Arr::wrap($roles);
            $roles = array_map(fn ($role) => $role instanceof \BackedEnum ? $role->value : $role, $roles);
            return $this->middleware('role:'.implode('|', $roles));
        });

        Route::macro('permission', function ($permissions = []) {
            $permissions = Arr::wrap($permissions);
            $permissions = array_map(fn ($permission) => $permission instanceof \BackedEnum ? $permission->value : $permission, $permissions);
            return $this->middleware('permission:'.implode('|', $permissions));
        });
    }

    protected function getMigrationFileName(string $migrationFileName): string
    {
        $timestamp = date('Y_m_d_His');
        $filesystem = $this->app->make(Filesystem::class);

        return Collection::make([$this->app->databasePath().DIRECTORY_SEPARATOR.'migrations'.DIRECTORY_SEPARATOR])
            ->flatMap(fn ($path) => $filesystem->glob($path.'*_'.$migrationFileName))
            ->push($this->app->databasePath()."/migrations/{$timestamp}_{$migrationFileName}")
            ->first();
    }

    protected function registerAbout(): void
    {
        if (! class_exists(InstalledVersions::class) || ! class_exists(AboutCommand::class)) {
            return;
        }

        $features = [
            'Teams' => 'omniguard.teams',
            'Wildcard' => 'omniguard.enable_wildcard_permission',
            'Octane' => 'omniguard.register_octane_reset_listener',
            'Impersonation' => 'omniguard.impersonation.enabled',
        ];

        $config = $this->app['config'];

        AboutCommand::add('OmniGuard', static fn () => [
            'Features Enabled' => collect($features)
                ->filter(fn (string $key): bool => $config->get($key, false))
                ->keys()
                ->whenEmpty(fn (Collection $collection) => $collection->push('Standard'))
                ->join(', '),
            'Version' => InstalledVersions::getPrettyVersion('omniguard/omniguard') ?? 'v1.0.0',
        ]);
    }
}
