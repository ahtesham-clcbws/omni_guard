<?php

namespace OmniGuard\Data\Support\Validation\References;

use OmniGuard\Data\Support\Validation\ValidationPath;

class FieldReference
{
    public function __construct(
        public readonly string $name,
        public readonly bool $fromRoot = false,
    ) {
    }

    public function getValue(ValidationPath $path): string
    {
        return $this->fromRoot
            ? $this->name
            : $path->property($this->name)->get();
    }
}
