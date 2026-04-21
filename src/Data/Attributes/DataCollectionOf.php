<?php

namespace OmniGuard\Data\Attributes;

use Attribute;
use OmniGuard\Data\Contracts\BaseData;
use OmniGuard\Data\Exceptions\CannotFindDataClass;

#[Attribute(Attribute::TARGET_PROPERTY)]
class DataCollectionOf
{
    public function __construct(
        /** @var class-string<\OmniGuard\Data\Contracts\BaseData> $class */
        public string $class
    ) {
        if (! is_subclass_of($this->class, BaseData::class)) {
            throw new CannotFindDataClass("Class {$this->class} given does not implement `BaseData::class`");
        }
    }
}
