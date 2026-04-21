<?php

namespace OmniGuard\Data\Attributes;

use Attribute;
use OmniGuard\Data\Attributes\Concerns\ResolvesPropertyForInjectedValue;
use OmniGuard\Data\Support\Creation\CreationContext;
use OmniGuard\Data\Support\DataProperty;

#[Attribute(Attribute::TARGET_PROPERTY)]
class FromRouteParameterProperty extends FromRouteParameter
{
    use ResolvesPropertyForInjectedValue;

    public function __construct(
        string $routeParameter,
        public ?string $property = null,
        bool $replaceWhenPresentInPayload = true,
        /** @deprecated  */
        bool $replaceWhenPresentInBody = true
    ) {
        parent::__construct($routeParameter, $replaceWhenPresentInPayload, $replaceWhenPresentInBody);
    }

    public function resolve(
        DataProperty $dataProperty,
        mixed $payload,
        array $properties,
        CreationContext $creationContext
    ): mixed {
        return $this->resolvePropertyForInjectedValue(
            $dataProperty,
            $payload,
            $properties,
            $creationContext
        );
    }

    protected function getPropertyKey(): string|null
    {
        return $this->property;
    }
}
