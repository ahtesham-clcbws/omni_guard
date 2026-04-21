<?php

namespace OmniGuard\Scanner\Support;

use OmniGuard\Scanner\Data\DiscoveredStructure;
use OmniGuard\Scanner\Data\DiscoverProfileConfig;
use OmniGuard\Scanner\Discover;
use OmniGuard\Scanner\DiscoverWorkers\DiscoverWorker;
use OmniGuard\Scanner\DiscoverWorkers\SynchronousDiscoverWorker;
use SplFileInfo;
use Symfony\Component\Finder\Finder;

class StructuresResolver
{
    public function __construct(
        protected DiscoverWorker $discoverWorker = new SynchronousDiscoverWorker(),
        protected StructureChainResolver $structureChainResolver = new StructureChainResolver()
    ) {
    }

    /** @return array<DiscoveredStructure>|array<string> */
    public function execute(Discover $profile): array
    {
        $structures = $this->discover(
            $profile->config
        );

        if ($profile->config->withChains) {
            $this->structureChainResolver->execute($structures);
        }

        $structures = array_filter(
            $structures,
            fn (DiscoveredStructure $discovered) => $profile->config->conditions->satisfies($discovered)
        );

        if ($profile->config->full === false) {
            $structures = array_map(
                fn (DiscoveredStructure $discovered) => $discovered->getFcqn(),
                $structures
            );
        }

        return array_values($structures);
    }

    /** @return array<DiscoveredStructure> */
    protected function discover(DiscoverProfileConfig $config): array
    {
        if (empty($config->directories)) {
            return [];
        }

        $finder = (new Finder())->files();

        if ($config->sort) {
            $config->sort->apply($finder);
        }

        if ($config->reverseSorting) {
            $finder->reverseSorting();
        }

        $files = $finder->in($config->directories);

        $filenames = collect($files)
            ->reject(fn (SplFileInfo $file) => in_array($file->getPathname(), $config->ignoredFiles) || $file->getExtension() !== 'php')
            ->map(fn (SplFileInfo $file) => $file->getPathname())
            ->values();

        return $this->discoverWorker->run($filenames, $config);
    }
}
