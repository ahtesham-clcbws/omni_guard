<?php

namespace OmniGuard\Data\Exceptions;

use Exception;
use OmniGuard\Data\Support\DataProperty;

class CannotSetComputedValue extends Exception
{
    public static function create(DataProperty $property): self
    {
        return new self("Cannot set property {$property->className}::\${$property->name} because it is a computed property.");
    }
}
