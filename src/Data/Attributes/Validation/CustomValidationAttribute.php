<?php

namespace OmniGuard\Data\Attributes\Validation;

use OmniGuard\Data\Support\Validation\ValidationPath;
use OmniGuard\Data\Support\Validation\ValidationRule;

abstract class CustomValidationAttribute extends ValidationRule
{
    /**
     * @return array<object|string>|object|string
     */
    abstract public function getRules(ValidationPath $path): array|object|string;
}
