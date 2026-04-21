<?php

namespace OmniGuard\Data\Attributes;

use OmniGuard\Data\Casts\Cast;

interface GetsCast
{
    public function get(): Cast;
}
