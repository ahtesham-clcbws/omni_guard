<?php

namespace OmniGuard\Data\Support;

use OmniGuard\Data\Resolvers\DataCollectableFromSomethingResolver;
use OmniGuard\Data\Resolvers\DataFromSomethingResolver;
use OmniGuard\Data\Resolvers\DataValidatorResolver;
use OmniGuard\Data\Resolvers\DecoupledPartialResolver;
use OmniGuard\Data\Resolvers\RequestQueryStringPartialsResolver;
use OmniGuard\Data\Resolvers\TransformedDataCollectableResolver;
use OmniGuard\Data\Resolvers\TransformedDataResolver;
use OmniGuard\Data\Resolvers\ValidatedPayloadResolver;
use OmniGuard\Data\Support\Factories\DataClassFactory;

class DataContainer
{
    protected static self $instance;

    protected ?TransformedDataResolver $transformedDataResolver = null;

    protected ?TransformedDataCollectableResolver $transformedDataCollectableResolver = null;

    protected ?RequestQueryStringPartialsResolver $requestQueryStringPartialsResolver = null;

    protected ?DataFromSomethingResolver $dataFromSomethingResolver = null;

    protected ?DataCollectableFromSomethingResolver $dataCollectableFromSomethingResolver = null;

    protected ?DataValidatorResolver $dataValidatorResolver = null;

    protected ?ValidatedPayloadResolver $validatedPayloadResolver = null;

    protected ?DataClassFactory $dataClassFactory = null;

    protected ?DecoupledPartialResolver $decoupledPartialResolver = null;

    private function __construct()
    {
    }

    public static function get(): DataContainer
    {
        if (! isset(static::$instance)) {
            static::$instance = new self();
        }

        return static::$instance;
    }

    public function transformedDataResolver(): TransformedDataResolver
    {
        return $this->transformedDataResolver ??= app(TransformedDataResolver::class);
    }

    public function transformedDataCollectableResolver(): TransformedDataCollectableResolver
    {
        return $this->transformedDataCollectableResolver ??= app(TransformedDataCollectableResolver::class);
    }

    public function requestQueryStringPartialsResolver(): RequestQueryStringPartialsResolver
    {
        return $this->requestQueryStringPartialsResolver ??= app(RequestQueryStringPartialsResolver::class);
    }

    public function dataFromSomethingResolver(): DataFromSomethingResolver
    {
        return $this->dataFromSomethingResolver ??= app(DataFromSomethingResolver::class);
    }

    public function dataValidatorResolver(): DataValidatorResolver
    {
        return $this->dataValidatorResolver ??= app(DataValidatorResolver::class);
    }

    public function validatedPayloadResolver(): ValidatedPayloadResolver
    {
        return $this->validatedPayloadResolver ??= app(ValidatedPayloadResolver::class);
    }

    public function dataCollectableFromSomethingResolver(): DataCollectableFromSomethingResolver
    {
        return $this->dataCollectableFromSomethingResolver ??= app(DataCollectableFromSomethingResolver::class);
    }

    public function dataClassFactory(): DataClassFactory
    {
        return $this->dataClassFactory ??= app(DataClassFactory::class);
    }

    public function decoupledPartialResolver(): DecoupledPartialResolver
    {
        return $this->decoupledPartialResolver ??= app(DecoupledPartialResolver::class);
    }

    public function reset()
    {
        $this->transformedDataResolver = null;
        $this->transformedDataCollectableResolver = null;
        $this->requestQueryStringPartialsResolver = null;
        $this->dataFromSomethingResolver = null;
        $this->dataCollectableFromSomethingResolver = null;
        $this->dataClassFactory = null;
        $this->decoupledPartialResolver = null;
        $this->dataValidatorResolver = null;
        $this->validatedPayloadResolver = null;
    }
}
