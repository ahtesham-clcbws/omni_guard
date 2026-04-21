<?php

namespace OmniGuard\Data\Exceptions;

use Exception;
use OmniGuard\Data\Support\DataProperty;

class CannotCastEnum extends Exception
{
    public static function create(string $type, mixed $value, DataProperty $property): self
    {
        return new self("Could not cast enum for property {$property->className}::{$property->name} with value `{$value}` into a `{$type}`");
    }
}
