<?php

namespace OmniGuard\Data\Concerns;

use Illuminate\Support\Arr;
use OmniGuard\Data\Resolvers\EmptyDataResolver;

trait EmptyData
{
    public static function empty(
        array $extra = [],
        mixed $replaceNullValuesWith = null,
        ?array $except = null,
        ?array $only = null,
    ): array {
        $emptyData = app(EmptyDataResolver::class)->execute(static::class, $extra, $replaceNullValuesWith);

        if ($only !== null) {
            $emptyData = Arr::only($emptyData, $only);
        }

        if ($except !== null) {
            $emptyData = Arr::except($emptyData, $except);
        }

        return $emptyData;
    }
}
