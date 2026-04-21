<?php

namespace OmniGuard\Scanner\StructureParsers;

use OmniGuard\Scanner\TokenParsers\MultiFileTokenParser;

class PhpTokenStructureParser implements StructureParser
{
    public function execute(array $filenames): array
    {
        return (new MultiFileTokenParser())->execute($filenames);
    }
}
