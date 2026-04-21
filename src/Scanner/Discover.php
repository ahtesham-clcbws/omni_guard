<?php

namespace OmniGuard\Scanner;

use OmniGuard\Scanner\Cache\DiscoverCacheDriver;
use OmniGuard\Scanner\Data\DiscoveredStructure;
use OmniGuard\Scanner\Data\DiscoverProfileConfig;
use OmniGuard\Scanner\DiscoverConditions\ExactDiscoverCondition;
use OmniGuard\Scanner\DiscoverWorkers\DiscoverWorker;
use OmniGuard\Scanner\DiscoverWorkers\ParallelDiscoverWorker;
use OmniGuard\Scanner\DiscoverWorkers\SynchronousDiscoverWorker;
use OmniGuard\Scanner\Enums\Sort;
use OmniGuard\Scanner\Exceptions\AmpNotInstalled;
use OmniGuard\Scanner\Exceptions\NoCacheConfigured;
use OmniGuard\Scanner\StructureParsers\PhpTokenStructureParser;
use OmniGuard\Scanner\StructureParsers\ReflectionStructureParser;
use OmniGuard\Scanner\StructureParsers\StructureParser;
use OmniGuard\Scanner\Support\Conditions\HasConditionsTrait;
use OmniGuard\Scanner\Support\LaravelDetector;
use OmniGuard\Scanner\Support\StructuresResolver;

class Discover
{
    use HasConditionsTrait;

    public readonly DiscoverProfileConfig $config;

    public static function in(string ...$directories): self
    {
        if (LaravelDetector::isRunningLaravel()) {
            return app(self::class, [
                'directories' => $directories,
            ]);
        }

        return new self(
            directories: $directories,
        );
    }

    /**
     * @param array<string> $directories
     * @param array<string> $ignoredFiles
     */
    public function __construct(
        array $directories = [],
        array $ignoredFiles = [],
        ExactDiscoverCondition $conditions = new ExactDiscoverCondition(),
        bool $full = false,
        DiscoverWorker $worker = new SynchronousDiscoverWorker(),
        ?DiscoverCacheDriver $cacheDriver = null,
        ?string $cacheId = null,
        bool $withChains = true,
        ?Sort $sort = null,
        bool $reverseSorting = false,
        StructureParser $structureParser = new PhpTokenStructureParser(),
        ?string $reflectionBasePath = null,
        ?string $reflectionRootNamespace = null,
    ) {
        $this->config = new DiscoverProfileConfig(
            directories: $directories,
            ignoredFiles: $ignoredFiles,
            full: $full,
            worker: $worker,
            cacheDriver: $cacheDriver,
            cacheId: $cacheId,
            withChains: $withChains,
            conditions: $conditions,
            sort: $sort,
            reverseSorting: $reverseSorting,
            structureParser: $structureParser,
            reflectionBasePath: $reflectionBasePath,
            reflectionRootNamespace: $reflectionRootNamespace,
        );
    }

    public function inDirectories(string ...$directories): self
    {
        array_push($this->config->directories, ...$directories);

        return $this;
    }

    public function ignoreFiles(string ...$ignoredFiles): self
    {
        array_push($this->config->ignoredFiles, ...$ignoredFiles);

        return $this;
    }

    public function sortBy(Sort $sort, bool $reverse = false): self
    {
        $this->config->sort = $sort;
        $this->config->reverseSorting = $reverse;

        return $this;
    }

    public function full(): self
    {
        $this->config->full = true;

        return $this;
    }

    public function usingWorker(DiscoverWorker $worker): self
    {
        $this->config->worker = $worker;

        return $this;
    }

    public function parallel(int $filesPerJob = 50): self
    {
        if (! function_exists('\Amp\Future\await')) {
            throw AmpNotInstalled::create();
        }

        return $this->usingWorker(new ParallelDiscoverWorker($filesPerJob));
    }

    public function withCache(string $id, ?DiscoverCacheDriver $cache = null): self
    {
        $this->config->cacheId = $id;

        if ($this->config->cacheDriver === null && $cache === null) {
            throw new NoCacheConfigured();
        }

        $this->config->cacheDriver = $cache;

        return $this;
    }

    public function withoutChains(bool $withoutChains = true): self
    {
        $this->config->withChains = ! $withoutChains;

        return $this;
    }

    public function useReflection(?string $basePath = null, ?string $rootNamespace = null): self
    {
        $this->config->structureParser = new ReflectionStructureParser($this->config);
        $this->config->reflectionBasePath = $basePath;
        $this->config->reflectionRootNamespace = $rootNamespace;

        return $this;
    }

    /** @return array<DiscoveredStructure>|array<string> */
    public function get(): array
    {
        if (! $this->config->shouldUseCache()) {
            return $this->getWithoutCache();
        }

        return $this->config->cacheDriver->has($this->config->cacheId)
            ? $this->config->cacheDriver->get($this->config->cacheId)
            : $this->cache();
    }

    /** @return array<DiscoveredStructure>|array<string> */
    public function getWithoutCache(): array
    {
        $discoverer = new StructuresResolver($this->config->worker);

        return $discoverer->execute($this);
    }

    /** @return array<DiscoveredStructure>|array<string> */
    public function cache(): array
    {
        $structures = $this->getWithoutCache();

        $this->config->cacheDriver->put(
            $this->config->cacheId,
            $structures
        );

        return $structures;
    }

    public function conditionsStore(): ExactDiscoverCondition
    {
        return $this->config->conditions;
    }
}
