<?php

namespace OmniGuard\Data\Normalizers\Normalized;

use OmniGuard\Data\Support\DataProperty;

interface Normalized
{
    public function getProperty(string $name, DataProperty $dataProperty): mixed;
}
