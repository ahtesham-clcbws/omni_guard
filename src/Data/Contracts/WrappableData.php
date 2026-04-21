<?php

namespace OmniGuard\Data\Contracts;

use OmniGuard\Data\Support\Wrapping\Wrap;

interface WrappableData
{
    public function withoutWrapping();

    public function wrap(string $key);

    public function getWrap(): Wrap;
}
