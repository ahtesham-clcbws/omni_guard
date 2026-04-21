<?php

namespace OmniGuard\Scanner\DiscoverConditions;

use OmniGuard\Scanner\Data\DiscoveredAttribute;
use OmniGuard\Scanner\Data\DiscoveredClass;
use OmniGuard\Scanner\Data\DiscoveredEnum;
use OmniGuard\Scanner\Data\DiscoveredInterface;
use OmniGuard\Scanner\Data\DiscoveredStructure;

class AttributeDiscoverCondition extends DiscoverCondition
{
    /** @var string[] */
    private array $classes;

    public function __construct(
        string ...$classes,
    ) {
        $this->classes = $classes;
    }

    public function satisfies(DiscoveredStructure $discoveredData): bool
    {
        $hasAttributes = $discoveredData instanceof DiscoveredInterface
            || $discoveredData instanceof DiscoveredEnum
            || $discoveredData instanceof DiscoveredClass;

        if (! $hasAttributes) {
            return false;
        }

        $foundAttributes = array_filter(
            $discoveredData->attributes,
            fn (DiscoveredAttribute $attribute) => in_array($attribute->class, $this->classes)
        );

        return count($foundAttributes) > 0;
    }
}
