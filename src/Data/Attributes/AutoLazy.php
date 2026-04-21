<?php

namespace OmniGuard\Data\Attributes;

use Attribute;
use Closure;
use OmniGuard\Data\Lazy;
use OmniGuard\Data\Support\DataProperty;

#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_PROPERTY)]
class AutoLazy
{
    public function build(Closure $castValue, mixed $payload, DataProperty $property, mixed $value): Lazy
    {
        return Lazy::create(fn () => $castValue($value));
    }
}
