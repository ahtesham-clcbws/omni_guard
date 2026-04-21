<?php

namespace OmniGuard\Data\Attributes;

use OmniGuard\Data\Support\Creation\CreationContext;
use OmniGuard\Data\Support\DataProperty;

interface InjectsPropertyValue
{
    public function resolve(
        DataProperty $dataProperty,
        mixed $payload,
        array $properties,
        CreationContext $creationContext
    ): mixed;

    public function shouldBeReplacedWhenPresentInPayload(): bool;
}
