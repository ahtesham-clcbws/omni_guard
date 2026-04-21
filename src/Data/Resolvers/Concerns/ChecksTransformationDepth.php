<?php

namespace OmniGuard\Data\Resolvers\Concerns;

use OmniGuard\Data\Exceptions\MaxTransformationDepthReached;
use OmniGuard\Data\Support\Transformation\TransformationContext;

trait ChecksTransformationDepth
{
    public function hasReachedMaxTransformationDepth(TransformationContext $context): bool
    {
        return $context->maxDepth !== null && $context->depth >= $context->maxDepth;
    }

    public function handleMaxDepthReached(TransformationContext $context): array
    {
        if ($context->throwWhenMaxDepthReached) {
            throw MaxTransformationDepthReached::create($context->maxDepth);
        }

        return [];
    }
}
