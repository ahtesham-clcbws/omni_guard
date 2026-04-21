<?php

namespace OmniGuard\Data\RuleInferrers;

use OmniGuard\Data\Attributes\Validation\Nullable;
use OmniGuard\Data\Support\DataProperty;
use OmniGuard\Data\Support\Validation\PropertyRules;
use OmniGuard\Data\Support\Validation\ValidationContext;

class NullableRuleInferrer implements RuleInferrer
{
    public function handle(
        DataProperty $property,
        PropertyRules $rules,
        ValidationContext $context,
    ): PropertyRules {
        if ($property->type->isNullable && ! $rules->hasType(Nullable::class)) {
            $rules->prepend(new Nullable());
        }

        return $rules;
    }
}
