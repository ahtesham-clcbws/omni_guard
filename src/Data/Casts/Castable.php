<?php

namespace OmniGuard\Data\Casts;

interface Castable
{
    /**
     * @param array $arguments
     */
    public static function dataCastUsing(array $arguments): Cast;
}
