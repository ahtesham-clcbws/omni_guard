<?php

namespace OmniGuard\Data;

class Optional
{
    public static function create(): Optional
    {
        return new self();
    }
}
