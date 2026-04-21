<?php

namespace OmniGuard\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_METHOD)]
class OmniResource
{
    /**
     * @param string|null $group The UI group (e.g., 'Finance', 'Academics')
     * @param string|null $icon The UI icon
     * @param string|null $name Explicit permission name override
     */
    public function __construct(
        public ?string $group = null,
        public ?string $icon = null,
        public ?string $name = null,
    ) {}
}
