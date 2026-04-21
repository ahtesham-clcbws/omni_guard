<?php

namespace OmniGuard\Scanner\Enums;

use Exception;
use PhpToken;
use OmniGuard\Scanner\Data\DiscoveredClass;
use OmniGuard\Scanner\Data\DiscoveredEnum;
use OmniGuard\Scanner\Data\DiscoveredInterface;
use OmniGuard\Scanner\Data\DiscoveredStructure;
use OmniGuard\Scanner\Data\DiscoveredTrait;

enum DiscoveredStructureType
{
    case ClassDefinition;
    case Enum;
    case Trait;
    case Interface;

    public static function fromToken(
        PhpToken $token
    ): ?self {
        return match ($token->id) {
            T_CLASS => self::ClassDefinition,
            T_ENUM => self::Enum,
            T_INTERFACE => self::Interface,
            T_TRAIT => self::Trait,
            default => null,
        };
    }

    /** @return class-string<DiscoveredStructure> */
    public function getDataClass(): string
    {
        return match ($this) {
            self::ClassDefinition => DiscoveredClass::class,
            self::Enum => DiscoveredEnum::class,
            self::Interface => DiscoveredInterface::class,
            self::Trait => DiscoveredTrait::class,
            default => throw new Exception('Unknown type'),
        };
    }
}
