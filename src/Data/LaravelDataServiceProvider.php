<?php

namespace Spatie\LaravelData;

use Livewire\Livewire;
use Spatie\LaravelData\Commands\DataMakeCommand;
use Spatie\LaravelData\Commands\DataStructuresCacheCommand;
use Spatie\LaravelData\Contracts\BaseData;
use Spatie\LaravelData\Resolvers\ContextResolver;
use Spatie\LaravelData\Support\Caching\DataStructureCache;
use Spatie\LaravelData\Support\DataConfig;
use Spatie\LaravelData\Support\Livewire\LivewireDataCollectionSynth;
use Spatie\LaravelData\Support\Livewire\LivewireDataSynth;
use Spatie\LaravelData\Support\VarDumper\VarDumperManager;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

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
