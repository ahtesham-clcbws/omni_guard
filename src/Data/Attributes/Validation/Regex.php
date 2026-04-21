<?php

namespace OmniGuard\Data\Attributes\Validation;

use Attribute;
use OmniGuard\Data\Support\Validation\References\ExternalReference;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::TARGET_PARAMETER)]
class Regex extends StringValidationAttribute
{
    public function __construct(protected string|ExternalReference $pattern)
    {
    }

    public static function keyword(): string
    {
        return 'regex';
    }

    public function parameters(): array
    {
        return [
            $this->pattern,
        ];
    }
}
