<?php

namespace OmniGuard\Data\Attributes\Validation;

use Attribute;
use BackedEnum;
use OmniGuard\Data\Support\Validation\References\ExternalReference;
use OmniGuard\Data\Support\Validation\References\FieldReference;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::TARGET_PARAMETER)]
class ExcludeUnless extends StringValidationAttribute
{
    protected FieldReference $field;

    public function __construct(
        string|FieldReference                                              $field,
        protected string|int|float|bool|BackedEnum|ExternalReference $value
    ) {
        $this->field = $this->parseFieldReference($field);
    }


    public static function keyword(): string
    {
        return 'exclude_unless';
    }

    public function parameters(): array
    {
        return [
            $this->field,
            $this->value,
        ];
    }
}
