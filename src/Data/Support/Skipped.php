<?php

namespace OmniGuard\Data\Support;

class Skipped
{
    protected static self $instance;

    public static function create(): self
    {
        return static::$instance ??= new static();
    }

    protected function __construct()
    {
    }
}
