<?php

namespace OmniGuard\Data\Support\VarDumper;

use OmniGuard\Data\Contracts\TransformableData;
use Symfony\Component\VarDumper\Cloner\AbstractCloner;

class VarDumperManager
{
    public function initialize(): void
    {
        AbstractCloner::$defaultCasters[TransformableData::class] = [DataVarDumperCaster::class, 'castDataObject'];
        AbstractCloner::$defaultCasters[TransformableData::class] = [DataVarDumperCaster::class, 'castDataCollectable'];
    }
}
