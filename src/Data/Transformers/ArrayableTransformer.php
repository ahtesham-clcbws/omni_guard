<?php

namespace OmniGuard\Data\Transformers;

use OmniGuard\Data\Support\DataProperty;
use OmniGuard\Data\Support\Transformation\TransformationContext;

class ArrayableTransformer implements Transformer
{
    public function transform(DataProperty $property, mixed $value, TransformationContext $context): array
    {
        return $value->toArray();
    }
}
