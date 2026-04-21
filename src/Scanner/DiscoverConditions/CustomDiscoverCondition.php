<?php

namespace OmniGuard\Scanner\DiscoverConditions;

use Closure;
use OmniGuard\Scanner\Data\DiscoveredStructure;

class CustomDiscoverCondition extends DiscoverCondition
{
    public function __construct(protected Closure $closure)
    {
    }

    public function satisfies(DiscoveredStructure $discoveredData): bool
    {
        return ($this->closure)($discoveredData);
    }
}
