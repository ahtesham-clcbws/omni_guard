<?php

namespace OmniGuard\Data\Exceptions;

use Exception;
use OmniGuard\Data\Casts\Cast;
use OmniGuard\Data\Casts\Castable;

class CannotCreateCastAttribute extends Exception
{
    public static function notACast(): self
    {
        $cast = Cast::class;

        return new self("WithCast attribute needs a cast that implements `{$cast}`");
    }

    public static function notACastable(): self
    {
        $cast = Castable::class;

        return new self("WithCastable attribute needs a class that implements `{$cast}`");
    }
}
