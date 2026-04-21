<?php

namespace OmniGuard\Data\Casts;

use Illuminate\Support\Enumerable;
use OmniGuard\Data\Support\Creation\CreationContext;
use OmniGuard\Data\Support\DataProperty;

/** @deprecated enable the iterable casts  */
class EnumerableCast implements Cast
{
    public function cast(DataProperty $property, mixed $value, array $properties, CreationContext $context): mixed
    {
        if (config('omniguard.data.features.cast_and_transform_iterables')) {
            return Uncastable::create();
        }

        if ($property->type->kind->isDataCollectable()) {
            return Uncastable::create();
        }

        if ($value instanceof Enumerable) {
            return $value;
        }

        /** @var class-string<Enumerable>|null $collectionType */
        $collectionType = $property->type->findAcceptedTypeForBaseType(Enumerable::class);

        if ($collectionType === null) {
            return collect($value);
        }

        return $collectionType::make($value);
    }
}
