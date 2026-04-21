<?php

namespace OmniGuard\Data\Transformers;

use DateTimeInterface;
use DateTimeZone;
use Illuminate\Support\Arr;
use OmniGuard\Data\Support\DataProperty;
use OmniGuard\Data\Support\Transformation\TransformationContext;

class DateTimeInterfaceTransformer implements Transformer
{
    protected string $format;

    public function __construct(
        ?string $format = null,
        protected ?string $setTimeZone = null
    ) {
        [$this->format] = Arr::wrap($format ?? config('omniguard.data.date_format'));
    }

    public function transform(DataProperty $property, mixed $value, TransformationContext $context): string
    {
        $this->setTimeZone ??= config('omniguard.data.date_timezone');

        /** @var DateTimeInterface $value */
        if ($this->setTimeZone) {
            $value = (clone $value)->setTimezone(new DateTimeZone($this->setTimeZone));
        }

        return $value->format(ltrim($this->format, '!'));
    }
}
