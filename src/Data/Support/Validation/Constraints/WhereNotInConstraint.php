<?php

namespace OmniGuard\Data\Support\Validation\Constraints;

use BackedEnum;
use Illuminate\Contracts\Support\Arrayable;
use OmniGuard\Data\Support\Validation\References\ExternalReference;

class WhereNotInConstraint extends DatabaseConstraint
{
    public function __construct(
        public readonly string|ExternalReference $column,
        public readonly Arrayable|BackedEnum|array|ExternalReference $values,
    ) {
    }

    public function apply(object $rule): void
    {
        $rule->whereNotIn(
            $this->parseExternalReference($this->column),
            $this->parseExternalReference($this->values),
        );
    }
}
