<?php

namespace OmniGuard\Data\Contracts;

use OmniGuard\Data\Support\Transformation\DataContext;

interface ContextableData
{
    public function getDataContext(): DataContext;
}
