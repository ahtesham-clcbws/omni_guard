<?php

namespace OmniGuard\Data\Concerns;

use Exception;
use OmniGuard\Data\Contracts\BaseData as BaseDataContract;
use OmniGuard\Data\Contracts\BaseDataCollectable as BaseDataCollectableContract;
use OmniGuard\Data\Contracts\ContextableData as ContextableDataContract;
use OmniGuard\Data\Contracts\IncludeableData as IncludeableDataContract;
use OmniGuard\Data\Support\DataContainer;
use OmniGuard\Data\Support\EloquentCasts\DataEloquentCast;
use OmniGuard\Data\Support\Transformation\TransformationContext;
use OmniGuard\Data\Support\Transformation\TransformationContextFactory;

trait TransformableData
{
    /** @return array<string, mixed> */
    public function transform(
        null|TransformationContextFactory|TransformationContext $transformationContext = null,
    ): array {
        $transformationContext = match (true) {
            $transformationContext instanceof TransformationContext => $transformationContext,
            $transformationContext instanceof TransformationContextFactory => $transformationContext->get($this),
            $transformationContext === null => new TransformationContext(
                maxDepth: config('omniguard.data.max_transformation_depth'),
                throwWhenMaxDepthReached: config('omniguard.data.throw_when_max_transformation_depth_reached')
            )
        };

        $resolver = match (true) {
            $this instanceof BaseDataContract => DataContainer::get()->transformedDataResolver(),
            $this instanceof BaseDataCollectableContract => DataContainer::get()->transformedDataCollectableResolver(),
            default => throw new Exception('Cannot transform data object')
        };

        if ($this instanceof IncludeableDataContract && $this instanceof ContextableDataContract) {
            $transformationContext->mergePartialsFromDataContext($this);
        }

        return $resolver->execute($this, $transformationContext);
    }

    /** @return array<array-key, mixed> */
    public function all(): array
    {
        return $this->transform(TransformationContextFactory::create()->withValueTransformation(false));
    }

    /** @return array<array-key, mixed> */
    public function toArray(): array
    {
        return $this->transform();
    }

    public function toJson($options = 0): string
    {
        return json_encode($this->transform(), $options);
    }

    /** @return array<array-key, mixed> */
    public function jsonSerialize(): array
    {
        return $this->transform();
    }

    public static function castUsing(array $arguments)
    {
        return new DataEloquentCast(static::class, $arguments);
    }
}
