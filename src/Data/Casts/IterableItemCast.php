<?php

namespace OmniGuard\Data\Casts;

use OmniGuard\Data\Support\Creation\CreationContext;
use OmniGuard\Data\Support\DataProperty;

interface IterableItemCast
{
    /**
     * @param array<string, mixed> $properties
     */
    public function castIterableItem(DataProperty $property, mixed $value, array $properties, CreationContext $context): mixed;
}
