<?php

namespace OmniGuard\Data\Attributes\Concerns;

use OmniGuard\Data\Exceptions\CannotFillFromRouteParameterPropertyUsingScalarValue;
use OmniGuard\Data\Support\Creation\CreationContext;
use OmniGuard\Data\Support\DataProperty;
use OmniGuard\Data\Support\Skipped;

trait ResolvesPropertyForInjectedValue
{
    abstract protected function getPropertyKey(): string|null;

    public function resolvePropertyForInjectedValue(
        DataProperty $dataProperty,
        mixed $payload,
        array $properties,
        CreationContext $creationContext
    ): mixed {
        $injected = parent::resolve(
            $dataProperty,
            $payload,
            $properties,
            $creationContext
        );

        if ($injected === Skipped::create()) {
            return $injected;
        }

        if (is_scalar($injected)) {
            throw CannotFillFromRouteParameterPropertyUsingScalarValue::create($dataProperty, $this);
        }

        return data_get($injected, $this->getPropertyKey() ?? $dataProperty->name);
    }
}
