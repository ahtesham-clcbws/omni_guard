<?php

namespace OmniGuard\Data\Attributes;

use Attribute;
use Closure;
use OmniGuard\Data\Lazy;
use OmniGuard\Data\Support\DataProperty;
use OmniGuard\Data\Support\Lazy\ClosureLazy;

#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_PROPERTY)]
class AutoClosureLazy extends AutoLazy
{
    public function build(Closure $castValue, mixed $payload, DataProperty $property, mixed $value): ClosureLazy
    {
        return Lazy::closure(fn () => $castValue($value));
    }
}
