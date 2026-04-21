<?php

namespace OmniGuard\Data\Attributes;

use Attribute;
use Closure;
use OmniGuard\Data\Lazy;
use OmniGuard\Data\Support\DataProperty;
use OmniGuard\Data\Support\Lazy\InertiaDeferred;

#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_PROPERTY)]
class AutoInertiaDeferred extends AutoLazy
{
    public function __construct(
        protected ?string $group = null,
    ) {
    }

    public function build(Closure $castValue, mixed $payload, DataProperty $property, mixed $value): InertiaDeferred
    {
        return Lazy::inertiaDeferred(fn () => $castValue($value), $this->group);
    }
}
