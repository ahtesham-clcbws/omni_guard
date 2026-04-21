<?php

namespace OmniGuard\Data\Support\Caching;

use OmniGuard\Data\Contracts\BaseData;
use OmniGuard\Data\Data;
use OmniGuard\Data\Dto;
use OmniGuard\Data\Resource;
use OmniGuard\Scanner\Discover;

class DataClassFinder
{
    public static function fromConfig(array $config): self
    {
        return new self(
            directories: array_filter($config['directories'], 'is_dir'),
            useReflection: $config['reflection_discovery']['enabled'],
            reflectionBasePath: $config['reflection_discovery']['base_path'],
            reflectionRootNamespace: $config['reflection_discovery']['root_namespace'],
        );
    }

    /**
     * @param array<string> $directories
     */
    public function __construct(
        protected array $directories,
        protected bool $useReflection,
        protected ?string $reflectionBasePath,
        protected ?string $reflectionRootNamespace,
    ) {
    }

    public function classes(): array
    {
        $discoverer = Discover::in(__DIR__.'/../../', ...$this->directories)
            ->implementing(BaseData::class);

        if ($this->useReflection) {
            $discoverer->useReflection($this->reflectionBasePath, $this->reflectionRootNamespace);
        }

        $classesToIgnore = [
            Dto::class,
            Data::class,
            Resource::class,
        ];

        $classes = array_filter(
            $discoverer->get(),
            fn (string $class) => ! in_array($class, $classesToIgnore)
        );

        return array_values($classes);
    }
}
