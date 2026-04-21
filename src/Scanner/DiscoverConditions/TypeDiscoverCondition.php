<?php

namespace OmniGuard\Scanner\DiscoverConditions;

use OmniGuard\Scanner\Data\DiscoveredStructure;
use OmniGuard\Scanner\Enums\DiscoveredStructureType;

class TypeDiscoverCondition extends DiscoverCondition
{
    /** @var DiscoveredStructureType[] */
    private array $types;

    public function __construct(DiscoveredStructureType ...$types)
    {
        $this->types = $types;
    }

    public function satisfies(DiscoveredStructure $discoveredData): bool
    {
        return in_array($discoveredData->getType(), $this->types);
    }
}
