<?php

namespace OmniGuard\Scanner\DiscoverConditions;

use OmniGuard\Scanner\Data\DiscoveredClass;
use OmniGuard\Scanner\Data\DiscoveredEnum;
use OmniGuard\Scanner\Data\DiscoveredInterface;
use OmniGuard\Scanner\Data\DiscoveredStructure;

class ImplementsWithoutChainDiscoverCondition extends DiscoverCondition
{
    /** @var string[] */
    private array $interfaces;

    public function __construct(
        string ...$interfaces
    ) {
        $this->interfaces = $interfaces;
    }

    public function satisfies(DiscoveredStructure $discoveredData): bool
    {
        if ($discoveredData instanceof DiscoveredClass || $discoveredData instanceof DiscoveredEnum) {
            $foundImplements = array_filter(
                $discoveredData->implements,
                fn (string $interface) => in_array($interface, $this->interfaces)
            );

            return count($foundImplements) > 0;
        }

        if ($discoveredData instanceof DiscoveredInterface) {
            $foundExtends = array_filter(
                $discoveredData->extends,
                fn (string $class) => in_array($class, $this->interfaces)
            );

            return count($foundExtends) > 0;
        }

        return false;
    }
}
