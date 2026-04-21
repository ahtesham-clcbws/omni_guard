<?php

namespace OmniGuard\Data\Casts;

use OmniGuard\Data\Support\Creation\CreationContext;
use OmniGuard\Data\Support\DataProperty;

interface Cast
{
    /**
     * @param array<string, mixed> $properties
     */
    public function cast(DataProperty $property, mixed $value, array $properties, CreationContext $context): mixed;
}
