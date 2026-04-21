<?php

namespace OmniGuard\Scanner\DiscoverWorkers;

use Illuminate\Support\Collection;
use OmniGuard\Scanner\Data\DiscoveredStructure;
use OmniGuard\Scanner\Data\DiscoverProfileConfig;

interface DiscoverWorker
{
    /**
     * @param Collection<int, string> $filenames
     * @param DiscoverProfileConfig $config
     *
     * @return array<DiscoveredStructure>
     */
    public function run(Collection $filenames, DiscoverProfileConfig $config): array;
}
