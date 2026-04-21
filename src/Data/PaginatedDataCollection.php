<?php

namespace OmniGuard\Data;

use Closure;
use Countable;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Support\Traits\Macroable;
use IteratorAggregate;
use OmniGuard\Data\Concerns\BaseDataCollectable;
use OmniGuard\Data\Concerns\ContextableData;
use OmniGuard\Data\Concerns\IncludeableData;
use OmniGuard\Data\Concerns\ResponsableData;
use OmniGuard\Data\Concerns\TransformableData;
use OmniGuard\Data\Concerns\WrappableData;
use OmniGuard\Data\Contracts\BaseDataCollectable as BaseDataCollectableContract;
use OmniGuard\Data\Contracts\ContextableData as ContextableDataContract;
use OmniGuard\Data\Contracts\IncludeableData as IncludeableDataContract;
use OmniGuard\Data\Contracts\ResponsableData as ResponsableDataContract;
use OmniGuard\Data\Contracts\TransformableData as TransformableDataContract;
use OmniGuard\Data\Contracts\WrappableData as WrappableDataContract;
use OmniGuard\Data\Exceptions\CannotCastData;
use OmniGuard\Data\Exceptions\PaginatedCollectionIsAlwaysWrapped;
use OmniGuard\Data\Support\EloquentCasts\DataCollectionEloquentCast;

/**
 * @template TKey of array-key
 * @template TValue
 *
 * @implements IteratorAggregate<TKey, TValue>
 */
class PaginatedDataCollection implements Responsable, BaseDataCollectableContract, TransformableDataContract, ContextableDataContract, ResponsableDataContract, IncludeableDataContract, WrappableDataContract, IteratorAggregate, Countable
{
    use ResponsableData;
    use IncludeableData;
    use WrappableData;
    use TransformableData;

    /** @use \OmniGuard\Data\Concerns\BaseDataCollectable<TKey, TValue> */
    use BaseDataCollectable;
    use ContextableData;

    use Macroable;

    protected Paginator $items;

    /**
     * @param class-string<TValue> $dataClass
     * @param Paginator $items
     */
    public function __construct(
        public readonly string $dataClass,
        Paginator $items
    ) {
        $this->items = $items->through(
            fn ($item) => $item instanceof $this->dataClass ? $item : $this->dataClass::from($item)
        );
    }

    /**
     * @param Closure(TValue, TKey): TValue $through
     *
     * @return static<TKey, TValue>
     */
    public function through(Closure $through): static
    {
        $clone = clone $this;

        $clone->items = $clone->items->through($through);

        return $clone;
    }

    /** @return Paginator<TKey, TValue> */
    public function items(): Paginator
    {
        return $this->items;
    }

    public static function castUsing(array $arguments)
    {
        if (count($arguments) !== 1) {
            throw CannotCastData::dataCollectionTypeRequired();
        }

        return new DataCollectionEloquentCast(current($arguments));
    }

    public function withoutWrapping(): static
    {
        throw PaginatedCollectionIsAlwaysWrapped::create();
    }
}
