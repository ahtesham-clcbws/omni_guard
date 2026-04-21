<?php

namespace OmniGuard\Data\Commands;

use Illuminate\Console\Command;
use ReflectionClass;
use OmniGuard\Data\Support\Caching\CachedDataConfig;
use OmniGuard\Data\Support\Caching\DataClassFinder;
use OmniGuard\Data\Support\Caching\DataStructureCache;
use OmniGuard\Data\Support\Factories\DataClassFactory;

class DataStructuresCacheCommand extends Command
{
    protected $signature = 'data:cache-structures {--show-classes : Show the data classes cached}';

    protected $description = 'Cache the internal data structures';

    public function handle(
        DataStructureCache $dataStructureCache,
        DataClassFactory $dataClassFactory,
    ): void {
        if (config('omniguard.data.structure_caching.enabled') === false) {
            $this->error('Data structure caching is not enabled');

            return;
        }

        $this->components->info('Caching data structures...');

        $dataClasses = DataClassFinder::fromConfig(config('omniguard.data.structure_caching'))->classes();

        $cachedDataConfig = CachedDataConfig::createFromConfig(config('omniguard.data.));

        $dataStructureCache->storeConfig($cachedDataConfig);

        $progressBar = $this->output->createProgressBar(count($dataClasses));

        foreach ($dataClasses as $dataClassString) {
            $dataClass = $dataClassFactory->build(new ReflectionClass($dataClassString));

            $dataClass->prepareForCache();

            $dataStructureCache->storeDataClass($dataClass);

            $progressBar->advance();
        }

        $progressBar->finish();

        $this->line(PHP_EOL);
        $this->line('Cached '.count($dataClasses).' data classes');

        if ($this->option('show-classes')) {
            $this->table(
                ['Data Class'],
                array_map(fn (string $dataClass) => [$dataClass], $dataClasses)
            );
        }
    }
}
