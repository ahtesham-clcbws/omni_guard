<?php

namespace OmniGuard\Data\Attributes;

use Attribute;
use OmniGuard\Data\Exceptions\CannotCreateTransformerAttribute;
use OmniGuard\Data\Transformers\Transformer;

#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_PROPERTY)]
class WithTransformer
{
    public array $arguments;

    public function __construct(
        /** @var class-string<\OmniGuard\Data\Transformers\Transformer> $transformerClass */
        public string $transformerClass,
        mixed ...$arguments
    ) {
        if (! is_a($this->transformerClass, Transformer::class, true)) {
            throw CannotCreateTransformerAttribute::notATransformer();
        }

        $this->arguments = $arguments;
    }

    public function get(): Transformer
    {
        return new ($this->transformerClass)(...$this->arguments);
    }
}
