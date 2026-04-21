<?php

namespace OmniGuard\Data\Concerns;

use OmniGuard\Data\Contracts\IncludeableData as IncludeableDataContract;
use OmniGuard\Data\Contracts\WrappableData as WrappableDataContract;
use OmniGuard\Data\Support\Partials\Partial;
use OmniGuard\Data\Support\Partials\PartialsCollection;
use OmniGuard\Data\Support\Transformation\DataContext;
use OmniGuard\Data\Support\Wrapping\Wrap;
use OmniGuard\Data\Support\Wrapping\WrapType;

trait ContextableData
{
    protected ?DataContext $_dataContext = null;

    public function getDataContext(): DataContext
    {
        if ($this->_dataContext === null) {
            $wrap = match (true) {
                method_exists($this, 'defaultWrap') => new Wrap(WrapType::Defined, $this->defaultWrap()),
                default => new Wrap(WrapType::UseGlobal),
            };

            $includePartials = null;
            $excludePartials = null;
            $onlyPartials = null;
            $exceptPartials = null;

            if ($this instanceof IncludeableDataContract) {
                if (! empty($this->includeProperties())) {
                    $includePartials = new PartialsCollection();
                }

                foreach ($this->includeProperties() as $key => $value) {
                    $includePartials->offsetSet(Partial::fromMethodDefinedKeyAndValue($key, $value));
                }

                if (! empty($this->excludeProperties())) {
                    $excludePartials = new PartialsCollection();
                }

                foreach ($this->excludeProperties() as $key => $value) {
                    $excludePartials->offsetSet(Partial::fromMethodDefinedKeyAndValue($key, $value));
                }

                if (! empty($this->onlyProperties())) {
                    $onlyPartials = new PartialsCollection();
                }

                foreach ($this->onlyProperties() as $key => $value) {
                    $onlyPartials->offsetSet(Partial::fromMethodDefinedKeyAndValue($key, $value));
                }

                if (! empty($this->exceptProperties())) {
                    $exceptPartials = new PartialsCollection();
                }

                foreach ($this->exceptProperties() as $key => $value) {
                    $exceptPartials->offsetSet(Partial::fromMethodDefinedKeyAndValue($key, $value));
                }
            }

            return $this->_dataContext = new DataContext(
                $includePartials,
                $excludePartials,
                $onlyPartials,
                $exceptPartials,
                $this instanceof WrappableDataContract ? $wrap : new Wrap(WrapType::UseGlobal),
            );
        }

        return $this->_dataContext;
    }

    public function setDataContext(
        ?DataContext $dataContext
    ): static {
        $this->_dataContext = $dataContext;

        return $this;
    }
}
