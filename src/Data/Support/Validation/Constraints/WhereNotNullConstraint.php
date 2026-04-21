<?php

namespace OmniGuard\Data\Support\Validation\Constraints;

use OmniGuard\Data\Support\Validation\References\ExternalReference;

class WhereNotNullConstraint extends DatabaseConstraint
{
    public function __construct(
        public readonly string|ExternalReference $column,
    ) {
    }

    public function apply(object $rule): void
    {
        $rule->whereNotNull(
            $this->parseExternalReference($this->column),
        );
    }
}
