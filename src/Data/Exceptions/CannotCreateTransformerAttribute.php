<?php

namespace OmniGuard\Data\Exceptions;

use Exception;
use OmniGuard\Data\Transformers\Transformer;

class CannotCreateTransformerAttribute extends Exception
{
    public static function notATransformer(): self
    {
        $transformer = Transformer::class;

        return new self("WithTransformer attribute needs a transformer that implements `{$transformer}`");
    }
}
