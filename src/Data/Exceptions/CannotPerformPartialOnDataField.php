<?php

namespace OmniGuard\Data\Exceptions;

use ErrorException;
use Exception;
use OmniGuard\Data\Support\DataClass;
use OmniGuard\Data\Support\Partials\PartialType;
use OmniGuard\Data\Support\Transformation\TransformationContext;

class CannotPerformPartialOnDataField extends Exception
{
    public static function create(
        ErrorException $exception,
        PartialType $partialType,
        string $field,
        DataClass $dataClass,
        TransformationContext $transformationContext,
    ): self {
        $message = "Tried to {$partialType->getVerb()} a non existing field `{$field}` on `{$dataClass->name}`.".PHP_EOL;
        $message .= 'Provided transformation context:'.PHP_EOL.PHP_EOL;
        $message .= $transformationContext;

        return new self(message: $message, previous: $exception);
    }
}
