<?php

namespace OmniGuard\Data\DataPipes;

use OmniGuard\Data\Support\Creation\CreationContext;
use OmniGuard\Data\Support\DataClass;

interface DataPipe
{
    /**
     * @param array<array-key, mixed> $properties
     *
     * @return array<array-key, mixed>
     */
    public function handle(mixed $payload, DataClass $class, array $properties, CreationContext $creationContext): array;
}
