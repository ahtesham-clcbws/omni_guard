<?php

namespace OmniGuard\Data;

use OmniGuard\Data\Contracts\BaseData;
use OmniGuard\Data\Exceptions\InvalidDataClass;

/**
 * @template T
 */
trait WithData
{
    /**
     * @return T
     */
    public function getData()
    {
        $dataClass = match (true) {
            /** @psalm-suppress UndefinedThisPropertyFetch */
            property_exists($this, 'dataClass') => $this->dataClass,
            method_exists($this, 'dataClass') => $this->dataClass(),
            default => null,
        };

        if (! is_a($dataClass, BaseData::class, true)) {
            throw InvalidDataClass::create($dataClass);
        }

        return $dataClass::from($this);
    }
}
