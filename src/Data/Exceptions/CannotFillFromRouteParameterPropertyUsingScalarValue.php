<?php

namespace OmniGuard\Data\Exceptions;

use Exception;
use Illuminate\Support\Str;
use OmniGuard\Data\Attributes\InjectsPropertyValue;
use OmniGuard\Data\Support\DataProperty;

class CannotFillFromRouteParameterPropertyUsingScalarValue extends Exception
{
    public static function create(DataProperty $property, InjectsPropertyValue $attribute): self
    {
        $attribute = Str::afterLast($attribute::class, '\\');

        return new self("Attribute {$attribute} cannot be used with injected scalar parameters for property {$property->className}::{$property->name}");
    }
}
