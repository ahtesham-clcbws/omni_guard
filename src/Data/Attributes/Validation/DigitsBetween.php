<?php

namespace OmniGuard\Data\Attributes\Validation;

use Attribute;
use OmniGuard\Data\Support\Validation\References\ExternalReference;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::TARGET_PARAMETER)]
class DigitsBetween extends StringValidationAttribute
{
    public function __construct(protected int|ExternalReference $min, protected int|ExternalReference $max)
    {
    }

    public static function keyword(): string
    {
        return 'digits_between';
    }

    public function parameters(): array
    {
        return [$this->min, $this->max];
    }
}
