<?php

namespace OmniGuard\Data\Attributes\Validation;

use Attribute;
use BackedEnum;
use Illuminate\Support\Arr;
use OmniGuard\Data\Support\Validation\References\ExternalReference;
use OmniGuard\Data\Support\Validation\References\FieldReference;
use OmniGuard\Data\Support\Validation\RequiringRule;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::TARGET_PARAMETER)]
class RequiredUnless extends StringValidationAttribute implements RequiringRule
{
    protected FieldReference $field;

    protected string|array $values;

    public function __construct(
        string|FieldReference                                $field,
        null|array|string|BackedEnum|ExternalReference ...$values
    ) {
        $this->field = $this->parseFieldReference($field);
        $this->values = Arr::flatten($values);
    }


    public static function keyword(): string
    {
        return 'required_unless';
    }

    public function parameters(): array
    {
        return [
            $this->field,
            $this->values,
        ];
    }
}
