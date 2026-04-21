<?php

namespace OmniGuard\Scanner\Data;

use OmniGuard\Scanner\Cache\DiscoverCacheDriver;
use OmniGuard\Scanner\DiscoverConditions\ExactDiscoverCondition;
use OmniGuard\Scanner\DiscoverWorkers\DiscoverWorker;
use OmniGuard\Scanner\Enums\Sort;
use OmniGuard\Scanner\StructureParsers\StructureParser;

class DiscoverProfileConfig
{
    /**
     * @param array<string> $directories
     * @param array<string> $ignoredFiles
     */
    public function __construct(
        public array $directories,
        public array $ignoredFiles,
        public bool $full,
        public DiscoverWorker $worker,
        public ?DiscoverCacheDriver $cacheDriver,
        public ?string $cacheId,
        public bool $withChains,
        public ExactDiscoverCondition $conditions,
        public ?Sort $sort,
        public bool $reverseSorting,
        public StructureParser $structureParser,
        public ?string $reflectionBasePath,
        public ?string $reflectionRootNamespace,
    ) {
    }

    public function shouldUseCache(): bool
    {
        return $this->cacheId !== null && $this->cacheDriver !== null;
    }
}
