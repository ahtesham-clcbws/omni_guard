<?php

namespace OmniGuard\Data\Transformers;

use OmniGuard\Data\Support\DataProperty;
use OmniGuard\Data\Support\Transformation\TransformationContext;

class EnumTransformer implements Transformer
{
    public function transform(DataProperty $property, mixed $value, TransformationContext $context): string|int
    {
        return $value->value;
    }
}
