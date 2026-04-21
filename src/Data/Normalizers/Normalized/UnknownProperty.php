<?php

namespace OmniGuard\Data\Normalizers\Normalized;

/**
 * A humble helper class representing a missing or undefined property in the data engine.
 */
class UnknownProperty
{
    private static ?self $instance = null;

    /**
     * Private constructor for the singleton helper.
     */
    private function __construct()
    {
    }

    /**
     * Create or retrieve the singleton instance of an unknown property.
     */
    public static function create(): self
    {
        return self::$instance ??= new self();
    }
}
