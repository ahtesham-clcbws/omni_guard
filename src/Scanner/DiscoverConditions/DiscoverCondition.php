<?php

namespace OmniGuard\Scanner\DiscoverConditions;

use OmniGuard\Scanner\Data\DiscoveredStructure;
use OmniGuard\Scanner\Support\Conditions\ConditionBuilder;

abstract class DiscoverCondition
{
    abstract public function satisfies(DiscoveredStructure $discoveredData): bool;

    public static function create(): ConditionBuilder
    {
        return new ConditionBuilder();
    }
}
