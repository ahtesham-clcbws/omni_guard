<?php

namespace OmniGuard\Data\RuleInferrers;

use OmniGuard\Data\Attributes\Validation\Sometimes;
use OmniGuard\Data\Support\DataProperty;
use OmniGuard\Data\Support\Validation\PropertyRules;
use OmniGuard\Data\Support\Validation\ValidationContext;

class SometimesRuleInferrer implements RuleInferrer
{
    public function handle(
        DataProperty $property,
        PropertyRules $rules,
        ValidationContext $context,
    ): PropertyRules {
        if ($property->type->isOptional && ! $rules->hasType(Sometimes::class)) {
            $rules->prepend(new Sometimes());
        }

        return $rules;
    }
}
