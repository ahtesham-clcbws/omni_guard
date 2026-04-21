<?php

namespace OmniGuard\Data\Attributes;

use Attribute;
use OmniGuard\Data\Casts\Cast;
use OmniGuard\Data\Casts\Castable;
use OmniGuard\Data\Casts\CastableCast;
use OmniGuard\Data\Exceptions\CannotCreateCastAttribute;

#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_PROPERTY)]
class WithCastable implements GetsCast
{
    public array $arguments;

    public function __construct(
        /** @var class-string<\OmniGuard\Data\Casts\Castable> $castableClass */
        public string $castableClass,
        mixed ...$arguments
    ) {
        if (! is_a($this->castableClass, Castable::class, true)) {
            throw CannotCreateCastAttribute::notACastable();
        }

        $this->arguments = $arguments;
    }

    public function get(): Cast
    {
        return new CastableCast(
            $this->castableClass,
            $this->arguments
        );
    }
}
