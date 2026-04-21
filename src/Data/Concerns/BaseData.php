<?php

namespace OmniGuard\Data\Concerns;

use Illuminate\Contracts\Pagination\CursorPaginator as CursorPaginatorContract;
use Illuminate\Contracts\Pagination\Paginator as PaginatorContract;
use Illuminate\Pagination\AbstractCursorPaginator;
use Illuminate\Pagination\AbstractPaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Enumerable;
use Illuminate\Support\LazyCollection;
use OmniGuard\Data\CursorPaginatedDataCollection;
use OmniGuard\Data\DataCollection;
use OmniGuard\Data\DataPipeline;
use OmniGuard\Data\DataPipes\AuthorizedDataPipe;
use OmniGuard\Data\DataPipes\CastPropertiesDataPipe;
use OmniGuard\Data\DataPipes\DefaultValuesDataPipe;
use OmniGuard\Data\DataPipes\InjectPropertyValuesPipe;
use OmniGuard\Data\DataPipes\MapPropertiesDataPipe;
use OmniGuard\Data\DataPipes\ValidatePropertiesDataPipe;
use OmniGuard\Data\PaginatedDataCollection;
use OmniGuard\Data\Support\Creation\CreationContext;
use OmniGuard\Data\Support\Creation\CreationContextFactory;
use OmniGuard\Data\Support\DataConfig;
use OmniGuard\Data\Support\DataProperty;

trait BaseData
{
    public static function optional(mixed ...$payloads): ?static
    {
        if (count($payloads) === 0) {
            return null;
        }

        foreach ($payloads as $payload) {
            if ($payload !== null) {
                return static::from(...$payloads);
            }
        }

        return null;
    }

    public static function from(mixed ...$payloads): static
    {
        return static::factory()->from(...$payloads);
    }

    public static function collect(mixed $items, ?string $into = null): array|DataCollection|PaginatedDataCollection|CursorPaginatedDataCollection|Enumerable|AbstractPaginator|PaginatorContract|AbstractCursorPaginator|CursorPaginatorContract|LazyCollection|Collection
    {
        return static::factory()->collect($items, $into);
    }

    public static function factory(?CreationContext $creationContext = null): CreationContextFactory
    {
        if ($creationContext) {
            return CreationContextFactory::createFromCreationContext(static::class, $creationContext);
        }

        return CreationContextFactory::createFromConfig(static::class);
    }

    public static function normalizers(): array
    {
        return config('omniguard.data.normalizers');
    }

    public static function pipeline(): DataPipeline
    {
        return DataPipeline::create()
            ->into(static::class)
            ->through(AuthorizedDataPipe::class)
            ->through(MapPropertiesDataPipe::class)
            ->through(InjectPropertyValuesPipe::class)
            ->through(ValidatePropertiesDataPipe::class)
            ->through(DefaultValuesDataPipe::class)
            ->through(CastPropertiesDataPipe::class);
    }

    public static function prepareForPipeline(array $properties): array
    {
        return $properties;
    }

    public function __sleep(): array
    {
        $dataClass = app(DataConfig::class)->getDataClass(static::class);

        return $dataClass
            ->properties
            ->reject(fn (DataProperty $property) => $property->isVirtual)
            ->map(fn (DataProperty $property) => $property->name)
            ->when($dataClass->appendable, fn (Collection $properties) => $properties->push('_additional'))
            ->when(property_exists($this, '_dataContext'), fn (Collection $properties) => $properties->push('_dataContext'))
            ->toArray();
    }
}
