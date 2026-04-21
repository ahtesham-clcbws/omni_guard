<?php

namespace OmniGuard\Exceptions;

use InvalidArgumentException;

class WildcardPermissionInvalidArgument extends InvalidArgumentException
{
    public static function create()
    {
        return new static(__('Wildcard permission must be string, permission id or permission instance'));
    }
}
