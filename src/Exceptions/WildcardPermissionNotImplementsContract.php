<?php

namespace OmniGuard\Exceptions;

use InvalidArgumentException;

class WildcardPermissionNotImplementsContract extends InvalidArgumentException
{
    public static function create()
    {
        return new static(__('Wildcard permission class must implement OmniGuard\\Permission\\Contracts\\Wildcard contract'));
    }
}
