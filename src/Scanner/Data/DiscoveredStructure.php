<?php

namespace OmniGuard\Scanner\Data;

use ReflectionClass;
use OmniGuard\Scanner\Enums\DiscoveredStructureType;

abstract class DiscoveredStructure
{
    public function __construct(
        public string $name,
        public string $file,
        public string $namespace,
    ) {
    }

    abstract public function getType(): DiscoveredStructureType;

    /**
     * @param ReflectionClass<object> $reflection
     */
    abstract public static function fromReflection(ReflectionClass $reflection): DiscoveredStructure;

    public function getFcqn(): string
    {
        return empty($this->namespace) ? $this->name : "{$this->namespace}\\{$this->name}";
    }
}
