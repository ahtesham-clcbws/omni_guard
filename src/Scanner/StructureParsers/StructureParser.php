<?php

namespace OmniGuard\Scanner\StructureParsers;

use OmniGuard\Scanner\Data\DiscoveredStructure;

interface StructureParser
{
    /**
     * @param array<string> $filenames
     *
     * @return array<DiscoveredStructure>
     */
    public function execute(array $filenames): array;
}
