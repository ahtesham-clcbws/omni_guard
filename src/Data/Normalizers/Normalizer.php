<?php

namespace OmniGuard\Data\Normalizers;

use OmniGuard\Data\Normalizers\Normalized\Normalized;

interface Normalizer
{
    public function normalize(mixed $value): null|array|Normalized;
}
