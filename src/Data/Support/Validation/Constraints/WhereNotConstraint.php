<?php

namespace OmniGuard\Data\Support\Validation\Constraints;

use OmniGuard\Data\Support\Validation\References\ExternalReference;

class WhereNotConstraint extends DatabaseConstraint
{
    public function __construct(
        public readonly string|ExternalReference $column,
        public readonly mixed $value,
    ) {
    }

    public function apply(object $rule): void
    {
        $rule->whereNot(
            $this->parseExternalReference($this->column),
            $this->parseExternalReference($this->value),
        );
    }
}
