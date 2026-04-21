<?php

namespace OmniGuard\Data\Attributes\Validation;

use Attribute;
use Illuminate\Support\Arr;
use OmniGuard\Data\Support\Validation\References\ExternalReference;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::TARGET_PARAMETER)]
class DateFormat extends StringValidationAttribute
{
    protected string|array $format;

    public function __construct(string|array|ExternalReference ...$format)
    {
        $this->format = Arr::flatten($format);
    }

    public static function keyword(): string
    {
        return 'date_format';
    }

    public function parameters(): array
    {
        return [$this->format];
    }
}
