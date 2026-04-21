<?php

namespace OmniGuard\Data\Contracts;

use Illuminate\Contracts\Pagination\CursorPaginator;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Pagination\AbstractCursorPaginator;
use Illuminate\Pagination\AbstractPaginator;
use Illuminate\Support\Enumerable;
use OmniGuard\Data\CursorPaginatedDataCollection;
use OmniGuard\Data\DataCollection;
use OmniGuard\Data\PaginatedDataCollection;

/**
 * @template TValue
 */
interface DeprecatedData
{
    /**
     * @param \Illuminate\Support\Enumerable<array-key, TValue>|TValue[]|\Illuminate\Pagination\AbstractPaginator|\Illuminate\Contracts\Pagination\Paginator|\Illuminate\Pagination\AbstractCursorPaginator|\Illuminate\Contracts\Pagination\CursorPaginator|\OmniGuard\Data\DataCollection<array-key, TValue> $items
     *
     * @return ($items is \Illuminate\Pagination\AbstractCursorPaginator|\Illuminate\Pagination\CursorPaginator ? \OmniGuard\Data\CursorPaginatedDataCollection<array-key, static> : ($items is \Illuminate\Pagination\Paginator|\Illuminate\Pagination\AbstractPaginator ? \OmniGuard\Data\PaginatedDataCollection<array-key, static> : \OmniGuard\Data\DataCollection<array-key, static>))
     */
    public static function collection(Enumerable|array|AbstractPaginator|Paginator|AbstractCursorPaginator|CursorPaginator|DataCollection $items): DataCollection|CursorPaginatedDataCollection|PaginatedDataCollection;
}
