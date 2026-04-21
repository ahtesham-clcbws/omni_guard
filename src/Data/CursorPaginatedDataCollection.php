<?php

namespace OmniGuard\Data;

use Closure;
use Countable;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Pagination\CursorPaginator;
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
 * @implements  IteratorAggregate<TKey, TValue>
 */
class CursorPaginatedDataCollection implements Responsable, BaseDataCollectableContract, TransformableDataContract, ContextableDataContract, ResponsableDataContract, IncludeableDataContract, WrappableDataContract, IteratorAggregate, Countable
{
    use ResponsableData;
    use IncludeableData;
    use WrappableData;
    use TransformableData;

    /** @use \OmniGuard\Data\Concerns\BaseDataCollectable<TKey, TValue> */
    use BaseDataCollectable;
    use ContextableData;

    use Macroable;

    /** @var CursorPaginator<TValue> */
    protected CursorPaginator $items;

    /**
     * @param class-string<TValue> $dataClass
     * @param CursorPaginator<TValue> $items
     */
    public function __construct(
        public readonly string $dataClass,
        CursorPaginator $items
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

    /**
     * @return CursorPaginator<TValue>
     */
    public function items(): CursorPaginator
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
