<?php

namespace OmniGuard\Data\RuleInferrers;

use OmniGuard\Data\Attributes\Validation\Present;
use OmniGuard\Data\Attributes\Validation\Sometimes;
use OmniGuard\Data\Support\DataProperty;
use OmniGuard\Data\Support\Validation\PropertyRules;
use OmniGuard\Data\Support\Validation\RequiringRule;
use OmniGuard\Data\Support\Validation\RuleNormalizer;
use OmniGuard\Data\Support\Validation\ValidationContext;
use OmniGuard\Data\Support\Validation\ValidationRule;

class AttributesRuleInferrer implements RuleInferrer
{
    public function __construct(protected RuleNormalizer $rulesDenormalizer)
    {
    }

    public function handle(
        DataProperty $property,
        PropertyRules $rules,
        ValidationContext $context,
    ): PropertyRules {
        foreach ($property->attributes->all(ValidationRule::class) as $rule) {
            if ($rule instanceof Present && $rules->hasType(RequiringRule::class)) {
                $rules->removeType(RequiringRule::class);
            }

            if ($rule instanceof RequiringRule && $rules->hasType(Sometimes::class)) {
                $rules->removeType(Sometimes::class);
            }

            $rules->add(
                ...$this->rulesDenormalizer->execute($rule)
            );
        }

        return $rules;
    }
}
