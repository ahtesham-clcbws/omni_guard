<?php

namespace OmniGuard\Data;

use Livewire\Livewire;
use OmniGuard\Data\Commands\DataMakeCommand;
use OmniGuard\Data\Commands\DataStructuresCacheCommand;
use OmniGuard\Data\Contracts\BaseData;
use OmniGuard\Data\Resolvers\ContextResolver;
use OmniGuard\Data\Support\Caching\DataStructureCache;
use OmniGuard\Data\Support\DataConfig;
use OmniGuard\Data\Support\Livewire\LivewireDataCollectionSynth;
use OmniGuard\Data\Support\Livewire\LivewireDataSynth;
use OmniGuard\Data\Support\VarDumper\VarDumperManager;
use OmniGuard\Support\PackageTools\Package;
use OmniGuard\Support\PackageTools\PackageServiceProvider;

class LaravelDataServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-data')
            ->hasCommand(DataMakeCommand::class)
            ->hasCommand(DataStructuresCacheCommand::class)
            ->hasConfigFile('data');
    }

    public function packageRegistered(): void
    {
        $this->app->singleton(
            DataStructureCache::class,
            fn () => new DataStructureCache(config('omniguard.data.structure_caching.cache'))
        );

        $this->app->singleton(
            DataConfig::class,
            function () {
                if (config('omniguard.data.structure_caching.enabled') === false || $this->app->runningUnitTests()) {
                    return DataConfig::createFromConfig(config('omniguard.data.));
                }

                return $this->app->make(DataStructureCache::class)->getConfig() ?? DataConfig::createFromConfig(config('omniguard.data.));
            }
        );

        $this->app->singleton(ContextResolver::class);

        $this->app->beforeResolving(BaseData::class, function ($class, $parameters, $app) {
            if ($app->has($class)) {
                return;
            }

            $app->bind(
                $class,
                fn ($container) => $class::from($container['request'])
            );
        });

        if (config('omniguard.data.livewire.enable_synths') && class_exists(Livewire::class)) {
            $this->registerLivewireSynths();
        }
    }

    protected function registerLivewireSynths(): void
    {
        Livewire::propertySynthesizer(LivewireDataSynth::class);
        Livewire::propertySynthesizer(LivewireDataCollectionSynth::class);
    }

    public function packageBooted(): void
    {
        $enableVarDumperCaster = match (config('omniguard.data.var_dumper_caster_mode')) {
            'enabled' => true,
            'development' => $this->app->environment('local', 'testing'),
            default => false,
        };

        if ($enableVarDumperCaster) {
            (new VarDumperManager())->initialize();
        }

        if (method_exists($this, 'optimizes')) {
            $this->optimizes(
                optimize: 'data:cache-structures',
                key: 'laravel-data',
            );
        }
    }
}
