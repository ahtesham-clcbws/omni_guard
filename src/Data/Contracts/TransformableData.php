<?php

namespace OmniGuard\Data\Contracts;

use Illuminate\Contracts\Database\Eloquent\Castable as EloquentCastable;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use JsonSerializable;
use OmniGuard\Data\Support\Transformation\TransformationContext;
use OmniGuard\Data\Support\Transformation\TransformationContextFactory;

interface TransformableData extends JsonSerializable, Jsonable, Arrayable, EloquentCastable
{
    public function transform(
        null|TransformationContextFactory|TransformationContext $transformationContext = null,
    ): array;

    public function all(): array;

    public function toArray(): array;

    public function toJson($options = 0): string;

    public function jsonSerialize(): array;

    public static function castUsing(array $arguments);
}
