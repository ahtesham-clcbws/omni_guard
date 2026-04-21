<?php

namespace OmniGuard\Data\RuleInferrers;

use BackedEnum;
use OmniGuard\Data\Attributes\Validation\ArrayType;
use OmniGuard\Data\Attributes\Validation\BooleanType;
use OmniGuard\Data\Attributes\Validation\Enum;
use OmniGuard\Data\Attributes\Validation\Numeric;
use OmniGuard\Data\Attributes\Validation\StringType;
use OmniGuard\Data\Support\DataProperty;
use OmniGuard\Data\Support\Validation\PropertyRules;
use OmniGuard\Data\Support\Validation\ValidationContext;

class BuiltInTypesRuleInferrer implements RuleInferrer
{
    public function handle(
        DataProperty $property,
        PropertyRules $rules,
        ValidationContext $context,
    ): PropertyRules {
        if ($property->type->type->acceptsType('int')) {
            $rules->add(new Numeric());
        }

        if ($property->type->type->acceptsType('string')) {
            $rules->add(new StringType());
        }

        if ($property->type->type->acceptsType('bool')) {
            $rules->add(new BooleanType());
        }

        if ($property->type->type->acceptsType('float')) {
            $rules->add(new Numeric());
        }

        if ($property->type->type->acceptsType('array')) {
            $rules->add(new ArrayType());
        }

        if ($enumClass = $property->type->type->findAcceptedTypeForBaseType(BackedEnum::class)) {
            $rules->add(new Enum($enumClass));
        }

        return $rules;
    }
}
