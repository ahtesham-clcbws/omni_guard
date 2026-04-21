<?php

namespace OmniGuard\Data\Attributes\Validation;

use Attribute;
use Illuminate\Support\Arr;
use OmniGuard\Data\Support\Validation\References\ExternalReference;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::TARGET_PARAMETER)]
class Url extends StringValidationAttribute
{
    protected array $protocols;

    public function __construct(
        string|array|ExternalReference ...$protocols
    ) {
        $this->protocols = Arr::flatten($protocols);
    }

    public static function keyword(): string
    {
        return 'url';
    }

    public function parameters(): array
    {
        return $this->protocols;
    }
}
