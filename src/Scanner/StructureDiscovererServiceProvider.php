<?php

namespace OmniGuard\Scanner;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use OmniGuard\Scanner\Commands\CacheStructureScoutsCommand;
use OmniGuard\Scanner\Commands\ClearStructureScoutsCommand;
use OmniGuard\Scanner\Support\DiscoverCacheDriverFactory;

class StructureDiscovererServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('structure-discoverer')
            ->hasConfigFile()
            ->hasCommand(CacheStructureScoutsCommand::class)
            ->hasCommand(ClearStructureScoutsCommand::class);
    }

    public function packageRegistered(): void
    {
        $this->app->bind(Discover::class, fn ($app, $provided) => new Discover(
            directories: $provided['directories'] ?? [],
            ignoredFiles: config('structure-discoverer.ignored_files'),
            cacheDriver: DiscoverCacheDriverFactory::create(config('structure-discoverer.cache')),
        ));
    }
}
