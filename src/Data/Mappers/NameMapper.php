<?php

namespace OmniGuard\Data\Mappers;

interface NameMapper
{
    public function map(string|int $name): string|int;
}
