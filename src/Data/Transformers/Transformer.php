<?php

namespace OmniGuard\Data\Transformers;

use OmniGuard\Data\Support\DataProperty;
use OmniGuard\Data\Support\Transformation\TransformationContext;

interface Transformer
{
    public function transform(DataProperty $property, mixed $value, TransformationContext $context): mixed;
}
