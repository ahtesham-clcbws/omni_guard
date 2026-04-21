<?php

namespace OmniGuard\Data\Support;

use OmniGuard\Data\DataPipes\DataPipe;
use OmniGuard\Data\Exceptions\CannotCreateData;
use OmniGuard\Data\Normalizers\Normalized\Normalized;
use OmniGuard\Data\Normalizers\Normalized\UnknownProperty;
use OmniGuard\Data\Normalizers\Normalizer;
use OmniGuard\Data\Support\Creation\CreationContext;

class ResolvedDataPipeline
{
    /**
     * @param array<Normalizer> $normalizers
     * @param array<DataPipe> $pipes
     */
    public function __construct(
        protected array $normalizers,
        protected array $pipes,
        protected DataClass $dataClass,
    ) {
    }

    public function execute(mixed $value, CreationContext $creationContext): array
    {
        $normalizedValue = $this->normalize($value);

        $normalizedValue = $this->transformNormalizedToArray(
            $normalizedValue,
            $creationContext,
        );

        return $this->runPipelineOnNormalizedValue($value, $normalizedValue, $creationContext);
    }

    public function normalize(mixed $value): array|Normalized
    {
        $properties = null;

        foreach ($this->normalizers as $normalizer) {
            $properties = $normalizer->normalize($value);

            if ($properties !== null) {
                break;
            }
        }

        if ($properties === null) {
            throw CannotCreateData::noNormalizerFound($this->dataClass->name, $value);
        }

        return $properties;
    }

    public function runPipelineOnNormalizedValue(
        mixed $value,
        array|Normalized $normalizedValue,
        CreationContext $creationContext
    ): array {
        $properties = ($this->dataClass->name)::prepareForPipeline(
            $this->transformNormalizedToArray($normalizedValue, $creationContext)
        );

        foreach ($this->pipes as $pipe) {
            $piped = $pipe->handle($value, $this->dataClass, $properties, $creationContext);

            $properties = $piped;
        }

        return $properties;
    }

    public function transformNormalizedToArray(
        Normalized|array $normalized,
        CreationContext $creationContext,
    ): array {
        if (! $normalized instanceof Normalized) {
            return $normalized;
        }

        $properties = [];

        $dataClassToNormalize = $creationContext->dataClass !== $this->dataClass->name
            ? app(DataConfig::class)->getDataClass($creationContext->dataClass)
            : $this->dataClass;

        foreach ($dataClassToNormalize->properties as $property) {
            $name = $creationContext->mapPropertyNames && $property->inputMappedName
                ? $property->inputMappedName
                : $property->name;

            $value = $normalized->getProperty($name, $property);

            if ($value === UnknownProperty::create()) {
                continue;
            }

            $properties[$name] = $value;
        }

        return $properties;
    }
}
