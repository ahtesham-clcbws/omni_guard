<?php

namespace OmniGuard\Data\Attributes\Validation;

use OmniGuard\Data\Support\Validation\ValidationPath;

abstract class ObjectValidationAttribute extends ValidationAttribute
{
    abstract public function getRule(ValidationPath $path): object|string;
}
