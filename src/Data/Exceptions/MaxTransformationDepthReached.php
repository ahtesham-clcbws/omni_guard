<?php

namespace OmniGuard\Data\Exceptions;

use Exception;

class MaxTransformationDepthReached extends Exception
{
    public static function create(int $depth): self
    {
        return new self("Max transformation depth of {$depth} reached.");
    }
}
