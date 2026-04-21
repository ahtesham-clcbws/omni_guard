<?php

namespace OmniGuard\Scanner\DiscoverConditions;

use OmniGuard\Scanner\Data\DiscoveredClass;
use OmniGuard\Scanner\Data\DiscoveredStructure;

class ExtendsWithoutChainDiscoverCondition extends DiscoverCondition
{
    /** @var string[] */
    private array $classes;

    public function __construct(string ...$classes)
    {
        $this->classes = $classes;
    }

    public function satisfies(DiscoveredStructure $discoveredData): bool
    {
        if ($discoveredData instanceof DiscoveredClass) {
            return in_array($discoveredData->extends, $this->classes);
        }

        return false;
    }
}
