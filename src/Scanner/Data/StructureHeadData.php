<?php

namespace OmniGuard\Scanner\Data;

class StructureHeadData
{
    /**
     * @param array<string> $extends
     * @param array<string> $implements
     */
    public function __construct(
        public array $extends,
        public array $implements,
    ) {
    }
}
