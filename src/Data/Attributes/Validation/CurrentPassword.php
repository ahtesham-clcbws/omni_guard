<?php

namespace OmniGuard\Data\Attributes\Validation;

use Attribute;
use OmniGuard\Data\Support\Validation\References\ExternalReference;
use OmniGuard\Data\Tests\Fakes\Enums\DummyBackedEnum;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::TARGET_PARAMETER)]
class CurrentPassword extends StringValidationAttribute
{
    public function __construct(protected null|string|DummyBackedEnum|ExternalReference $guard = null)
    {
    }

    public static function keyword(): string
    {
        return 'current_password';
    }

    public function parameters(): array
    {
        return [$this->guard];
    }
}
