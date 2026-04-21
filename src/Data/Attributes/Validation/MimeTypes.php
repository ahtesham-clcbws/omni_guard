<?php

namespace OmniGuard\Data\Attributes\Validation;

use Attribute;
use Illuminate\Support\Arr;
use OmniGuard\Data\Support\Validation\References\ExternalReference;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::TARGET_PARAMETER)]
class MimeTypes extends StringValidationAttribute
{
    protected array $mimeTypes;

    public function __construct(string|array|ExternalReference ...$mimeTypes)
    {
        $this->mimeTypes = Arr::flatten($mimeTypes);
    }

    public static function keyword(): string
    {
        return 'mimetypes';
    }

    public function parameters(): array
    {
        return [$this->mimeTypes];
    }
}
