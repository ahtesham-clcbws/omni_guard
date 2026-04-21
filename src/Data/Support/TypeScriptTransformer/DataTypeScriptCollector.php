<?php

namespace OmniGuard\Data\Support\TypeScriptTransformer;

use ReflectionClass;
use OmniGuard\Data\Contracts\BaseData;
use OmniGuard\TypeScript\Collectors\Collector;
use OmniGuard\TypeScript\Structures\TransformedType;

class DataTypeScriptCollector extends Collector
{
    public function getTransformedType(ReflectionClass $class): ?TransformedType
    {
        if (! $class->isSubclassOf(BaseData::class)) {
            return null;
        }

        $transformer = new DataTypeScriptTransformer($this->config);

        return $transformer->transform($class, $class->getShortName());
    }
}
