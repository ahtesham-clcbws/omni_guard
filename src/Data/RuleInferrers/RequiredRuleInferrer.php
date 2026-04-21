<?php

namespace OmniGuard\Data\RuleInferrers;

use OmniGuard\Data\Attributes\Validation\Nullable;
use OmniGuard\Data\Attributes\Validation\Present;
use OmniGuard\Data\Attributes\Validation\Required;
use OmniGuard\Data\Support\DataProperty;
use OmniGuard\Data\Support\Validation\PropertyRules;
use OmniGuard\Data\Support\Validation\RequiringRule;
use OmniGuard\Data\Support\Validation\ValidationContext;

class RequiredRuleInferrer implements RuleInferrer
{
    public function handle(
        DataProperty $property,
        PropertyRules $rules,
        ValidationContext $context,
    ): PropertyRules {
        if ($this->shouldAddRule($property, $rules)) {
            $rules->prepend(new Required());
        }

        return $rules;
    }

    protected function shouldAddRule(DataProperty $property, PropertyRules $rules): bool
    {
        if ($property->type->isNullable || $property->type->isOptional) {
            return false;
        }

        if ($property->type->kind->isDataCollectable() && $rules->hasType(Present::class)) {
            return false;
        }

        if ($rules->hasType(Nullable::class)) {
            return false;
        }

        if ($rules->hasType(RequiringRule::class)) {
            return false;
        }

        return true;
    }
}
