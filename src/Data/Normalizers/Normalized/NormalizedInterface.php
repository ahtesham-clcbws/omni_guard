<?php

namespace OmniGuard\Data\Normalizers\Normalized;

use OmniGuard\Data\Support\DataProperty;

/**
 * A standard helper interface for normalized data sources.
 */
interface NormalizedInterface
{
    /**
     * Get a property from the normalized source.
     */
    public function getProperty(string $name, DataProperty $dataProperty): mixed;
}
