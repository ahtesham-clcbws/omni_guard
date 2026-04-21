<?php

namespace OmniGuard\Data\Support\Factories;

use ReflectionClass;
use ReflectionProperty;
use OmniGuard\Data\Attributes\AutoLazy;
use OmniGuard\Data\Attributes\Computed;
use OmniGuard\Data\Attributes\GetsCast;
use OmniGuard\Data\Attributes\Hidden;
use OmniGuard\Data\Attributes\PropertyForMorph;
use OmniGuard\Data\Attributes\WithCastAndTransformer;
use OmniGuard\Data\Attributes\WithoutValidation;
use OmniGuard\Data\Attributes\WithTransformer;
use OmniGuard\Data\Mappers\NameMapper;
use OmniGuard\Data\Optional;
use OmniGuard\Data\Resolvers\NameMappersResolver;
use OmniGuard\Data\Support\Annotations\DataIterableAnnotation;
use OmniGuard\Data\Support\DataProperty;

class DataPropertyFactory
{
    public function __construct(
        protected DataTypeFactory $typeFactory,
    ) {
    }

    public function build(
        ReflectionProperty $reflectionProperty,
        ReflectionClass $reflectionClass,
        bool $hasDefaultValue = false,
        mixed $defaultValue = null,
        ?NameMapper $classInputNameMapper = null,
        ?NameMapper $classOutputNameMapper = null,
        ?DataIterableAnnotation $classDefinedDataIterableAnnotation = null,
        ?AutoLazy $classAutoLazy = null,
    ): DataProperty {
        $attributes = DataAttributesCollectionFactory::buildFromReflectionProperty($reflectionProperty);

        $type = $this->typeFactory->buildProperty(
            $reflectionProperty->getType(),
            $reflectionClass,
            $reflectionProperty,
            $attributes,
            $classDefinedDataIterableAnnotation
        );

        $mappers = NameMappersResolver::create()->execute($attributes);

        $inputMappedName = match (true) {
            $mappers['inputNameMapper'] !== null => $mappers['inputNameMapper']->map($reflectionProperty->name),
            $classInputNameMapper !== null => $classInputNameMapper->map($reflectionProperty->name),
            default => null,
        };

        $outputMappedName = match (true) {
            $mappers['outputNameMapper'] !== null => $mappers['outputNameMapper']->map($reflectionProperty->name),
            $classOutputNameMapper !== null => $classOutputNameMapper->map($reflectionProperty->name),
            default => null,
        };

        if (! $reflectionProperty->isPromoted()) {
            $hasDefaultValue = $reflectionProperty->hasDefaultValue();
            $defaultValue = $hasDefaultValue ? $reflectionProperty->getDefaultValue() : null;
        }

        if ($hasDefaultValue && $defaultValue instanceof Optional) {
            $hasDefaultValue = false;
            $defaultValue = null;
        }

        $autoLazy = $attributes->first(AutoLazy::class);

        if ($classAutoLazy && $type->lazyType !== null && $autoLazy === null) {
            $autoLazy = $classAutoLazy;
        }

        /** @phpstan-ignore function.alreadyNarrowedType (`isVirtual()` doesn't exist in PHP versions earlier than 8.4) */
        $isVirtual = method_exists($reflectionProperty, 'isVirtual') && $reflectionProperty->isVirtual();

        $computed = $attributes->has(Computed::class) || $isVirtual;

        return new DataProperty(
            name: $reflectionProperty->name,
            className: $reflectionProperty->class,
            type: $type,
            validate: ! $computed && ! $attributes->has(WithoutValidation::class),
            computed: $computed,
            hidden: $attributes->has(Hidden::class),
            isPromoted: $reflectionProperty->isPromoted(),
            isReadonly: $reflectionProperty->isReadOnly(),
            isVirtual: $isVirtual,
            morphable: $attributes->has(PropertyForMorph::class),
            autoLazy: $autoLazy,
            hasDefaultValue: $hasDefaultValue,
            defaultValue: $defaultValue,
            cast: $attributes->first(GetsCast::class)?->get(),
            transformer: ($attributes->first(WithTransformer::class) ?? $attributes->first(WithCastAndTransformer::class))?->get(),
            inputMappedName: $inputMappedName,
            outputMappedName: $outputMappedName,
            attributes: $attributes,
        );
    }
}
