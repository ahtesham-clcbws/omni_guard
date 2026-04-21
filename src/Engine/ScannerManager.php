<?php

namespace OmniGuard\Engine;

use OmniGuard\Scanner\Discover;
use OmniGuard\Scanner\Data\DiscoveredClass;

class ScannerManager
{
    /**
     * Map of found classes and their suggested permissions.
     */
    protected array $registry = [];

    public function __construct(
        protected HeuristicMapper $mapper
    ) {}

    /**
     * Run a chunked scan across all configured paths.
     */
    public function scan(): array
    {
        $paths = config('omniguard.discovery.paths', [app_path()]);
        
        foreach ($paths as $path) {
            if (!is_dir($path)) continue;

            $classes = Discover::in($path)
                ->classes()
                ->get();

            foreach ($classes as $class) {
                $this->processClass($class);
            }
        }

        return $this->registry;
    }

    /**
     * Deep-probe a discovered class for permission candidates.
     */
    protected function processClass(string $class): void
    {
        if (!class_exists($class)) return;

        $reflection = new \ReflectionClass($class);
        $resourceName = $reflection->getShortName();

        // 1. Check for #[OmniResource] attribute
        $attributes = $reflection->getAttributes('OmniGuard\Attributes\OmniResource');
        if (!empty($attributes)) {
            $this->registry[$class] = $this->mapper->map('view', $resourceName);
            return;
        }

        // 2. Check for common resource patterns (Controllers/Livewire)
        if (str_ends_with($class, 'Controller') || str_contains($class, 'Livewire')) {
            foreach ($reflection->getMethods(\ReflectionMethod::IS_PUBLIC) as $method) {
                if ($method->class !== $class) continue; // Skip parents
                
                $permission = $this->mapper->map($method->name, $resourceName);
                $this->registry["{$class}@{$method->name}"] = $permission;
            }
        }
    }
}
