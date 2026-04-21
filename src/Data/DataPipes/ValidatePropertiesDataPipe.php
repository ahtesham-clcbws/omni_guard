<?php

namespace OmniGuard\Data\DataPipes;

use Illuminate\Http\Request;
use OmniGuard\Data\Support\Creation\CreationContext;
use OmniGuard\Data\Support\Creation\ValidationStrategy;
use OmniGuard\Data\Support\DataClass;

class ValidatePropertiesDataPipe implements DataPipe
{
    public function handle(
        mixed $payload,
        DataClass $class,
        array $properties,
        CreationContext $creationContext
    ): array {
        if ($creationContext->validationStrategy === ValidationStrategy::Disabled
            || $creationContext->validationStrategy === ValidationStrategy::AlreadyRan
        ) {
            return $properties;
        }

        if ($creationContext->validationStrategy === ValidationStrategy::OnlyRequests && ! $payload instanceof Request) {
            return $properties;
        }

        ($class->name)::validate($properties);

        $creationContext->validationStrategy = ValidationStrategy::AlreadyRan;

        return $properties;
    }
}
