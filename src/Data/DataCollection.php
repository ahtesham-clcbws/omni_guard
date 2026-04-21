<?php

namespace OmniGuard\Data;

use ArrayAccess;
use Countable;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Support\Collection;
use Illuminate\Support\Enumerable;
use Illuminate\Support\Traits\Macroable;
use IteratorAggregate;
use OmniGuard\Data\Concerns\BaseDataCollectable;
use OmniGuard\Data\Concerns\ContextableData;
use OmniGuard\Data\Concerns\EnumerableMethods;
use OmniGuard\Data\Concerns\IncludeableData;
use OmniGuard\Data\Concerns\ResponsableData;
use OmniGuard\Data\Concerns\TransformableData;
use OmniGuard\Data\Concerns\WrappableData;
use OmniGuard\Data\Contracts\BaseData;
use OmniGuard\Data\Contracts\BaseDataCollectable as BaseDataCollectableContract;
use OmniGuard\Data\Contracts\ContextableData as ContextableDataContract;
use OmniGuard\Data\Contracts\IncludeableData as IncludeableDataContract;
use OmniGuard\Data\Contracts\ResponsableData as ResponsableDataContract;
use OmniGuard\Data\Contracts\TransformableData as TransformableDataContract;
use OmniGuard\Data\Contracts\WrappableData as WrappableDataContract;
use OmniGuard\Data\Exceptions\CannotCastData;
use OmniGuard\Data\Exceptions\InvalidDataCollectionOperation;
use OmniGuard\Data\Support\EloquentCasts\DataCollectionEloquentCast;

/**
 * @template TKey of array-key
 * @template TValue
 *
 * @implements \ArrayAccess<TKey, TValue>
 * @implements  IteratorAggregate<TKey, TValue>
 */
class DataCollection implements Responsable, BaseDataCollectableContract, TransformableDataContract, ResponsableDataContract, IncludeableDataContract, WrappableDataContract, ContextableDataContract, IteratorAggregate, Countable, ArrayAccess
{
    /** @use \OmniGuard\Data\Concerns\BaseDataCollectable<TKey, TValue> */
    use BaseDataCollectable;
    use ResponsableData;
    use IncludeableData;
    use WrappableData;
    use TransformableData;
    use ContextableData;

    use Macroable;

    /** @use \OmniGuard\Data\Concerns\EnumerableMethods<TKey, TValue> */
    use EnumerableMethods;

    /** @var Enumerable<TKey, TValue> */
    protected Enumerable $items;

    /**
     * @param class-string<TValue> $dataClass
     * @param array|Enumerable<TKey, TValue>|DataCollection $items
     */
    public function __construct(
        public readonly string $dataClass,
        Enumerable|array|DataCollection|null $items
    ) {
        if (is_array($items) || is_null($items)) {
            $items = new Collection($items);
        }

        if ($items instanceof DataCollection) {
            $items = $items->toCollection();
        }

        $this->items = $items->map(
            fn ($item) => $item instanceof $this->dataClass ? $item : $this->dataClass::from($item)
        );
    }

    /**
     * @return array<TKey, TValue>
     */
    public function items(): array
    {
        return $this->items->all();
    }

    /**
     * @return Enumerable<TKey, TValue>
     */
    public function toCollection(): Enumerable
    {
        return $this->items;
    }

    /**
     * @param TKey $offset
     *
     * @return bool
     */
    public function offsetExists($offset): bool
    {
        if (! $this->items instanceof ArrayAccess) {
            throw InvalidDataCollectionOperation::create();
        }

        return $this->items->offsetExists($offset);
    }

    /**
     * @param TKey $offset
     *
     * @return TValue
     */
    public function offsetGet($offset): mixed
    {
        if (! $this->items instanceof ArrayAccess) {
            throw InvalidDataCollectionOperation::create();
        }

        $data = $this->items->offsetGet($offset);

        if ($data instanceof IncludeableDataContract
            && $data instanceof ContextableDataContract
        ) {
            $data->getDataContext()->mergePartials($this->getDataContext());
        }

        return $data;
    }

    /**
     * @param TKey|null $offset
     * @param TValue $value
     *
     * @return void
     */
    public function offsetSet($offset, $value): void
    {
        if (! $this->items instanceof ArrayAccess) {
            throw InvalidDataCollectionOperation::create();
        }

        $value = $value instanceof BaseData
            ? $value
            : $this->dataClass::from($value);

        $this->items->offsetSet($offset, $value);
    }

    /**
     * @param TKey $offset
     *
     * @return void
     */
    public function offsetUnset($offset): void
    {
        if (! $this->items instanceof ArrayAccess) {
            throw InvalidDataCollectionOperation::create();
        }

        $this->items->offsetUnset($offset);
    }

    public static function castUsing(array $arguments)
    {
        if (count($arguments) < 1) {
            throw CannotCastData::dataCollectionTypeRequired();
        }

        return new DataCollectionEloquentCast($arguments[0], static::class, array_slice($arguments, 1));
    }
}
