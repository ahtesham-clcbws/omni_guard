<?php

namespace OmniGuard\Data\Attributes;

use Attribute;
use Closure;
use OmniGuard\Data\Lazy;
use OmniGuard\Data\Support\DataProperty;
use OmniGuard\Data\Support\Lazy\InertiaLazy;

#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_PROPERTY)]
class AutoInertiaLazy extends AutoLazy
{
    public function build(Closure $castValue, mixed $payload, DataProperty $property, mixed $value): InertiaLazy
    {
        return Lazy::inertia(fn () => $castValue($value));
    }
}
