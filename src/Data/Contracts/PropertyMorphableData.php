<?php

namespace OmniGuard\Data\Contracts;

interface PropertyMorphableData
{
    public static function morph(array $properties): ?string;
}
