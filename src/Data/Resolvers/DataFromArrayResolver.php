<?php

namespace OmniGuard\Data\Resolvers;

use ArgumentCountError;
use OmniGuard\Data\Contracts\BaseData;
use OmniGuard\Data\Exceptions\CannotCreateData;
use OmniGuard\Data\Exceptions\CannotSetComputedValue;
use OmniGuard\Data\Optional;
use OmniGuard\Data\Support\DataClass;
use OmniGuard\Data\Support\DataConfig;
use OmniGuard\Data\Support\DataParameter;
use OmniGuard\Data\Support\DataProperty;

/**
 * @template TData of BaseData
 */
class DataFromArrayResolver
{
    public function __construct(protected DataConfig $dataConfig)
    {
    }

    /**
     * @param class-string<TData> $class
     *
     * @return TData
     */
    public function execute(string $class, array $properties): BaseData
    {
        $dataClass = $this->dataConfig->getDataClass($class);

        $data = $this->createData($dataClass, $properties);

        foreach ($dataClass->properties as $property) {
            if (
                $property->isPromoted
                || $property->isReadonly
                || ! array_key_exists($property->name, $properties)
            ) {
                continue;
            }

            if ($property->type->isOptional
                && isset($data->{$property->name})
                && $properties[$property->name] instanceof Optional
            ) {
                continue;
            }

            if ($property->computed
                && $property->type->isNullable
                && $properties[$property->name] === null
            ) {
                continue; // Nullable properties get assigned null by default
            }

            if ($property->computed
                && $property->type->isOptional
                && $properties[$property->name] instanceof Optional
            ) {
                continue; // Optional properties get assigned Optional by default
            }

            if ($property->computed) {
                if (! config('omniguard.data.features.ignore_exception_when_trying_to_set_computed_property_value')) {
                    throw CannotSetComputedValue::create($property);
                }

                continue; // Ignore the value being passed into the computed property and let it be recalculated
            }

            $data->{$property->name} = $properties[$property->name];
        }

        return $data;
    }

    protected function createData(
        DataClass $dataClass,
        array $properties,
    ) {
        $constructorParameters = $dataClass->constructorMethod?->parameters;

        if ($constructorParameters === null) {
            return new $dataClass->name();
        }

        $parameters = [];

        foreach ($constructorParameters as $parameter) {
            if (array_key_exists($parameter->name, $properties)) {
                $parameters[$parameter->name] = $properties[$parameter->name];

                continue;
            }

            if (! $parameter->isPromoted && $parameter->hasDefaultValue) {
                $parameters[$parameter->name] = $parameter->defaultValue;
            }
        }

        try {
            return new $dataClass->name(...$parameters);
        } catch (ArgumentCountError $error) {
            if ($this->isAnyParameterMissing($dataClass, array_keys($parameters))) {
                throw CannotCreateData::constructorMissingParameters(
                    $dataClass,
                    $parameters,
                    $error
                );
            } else {
                throw $error;
            }
        }
    }

    protected function isAnyParameterMissing(DataClass $dataClass, array $parameters): bool
    {
        return $dataClass
            ->constructorMethod
            ->parameters
            ->filter(fn (DataParameter|DataProperty $parameter) => ! $parameter->hasDefaultValue)
            ->pluck('name')
            ->diff($parameters)
            ->isNotEmpty();
    }
}
