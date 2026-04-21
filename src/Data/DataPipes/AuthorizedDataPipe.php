<?php

namespace OmniGuard\Data\DataPipes;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use OmniGuard\Data\Support\Creation\CreationContext;
use OmniGuard\Data\Support\DataClass;

class AuthorizedDataPipe implements DataPipe
{
    public function handle(
        mixed $payload,
        DataClass $class,
        array $properties,
        CreationContext $creationContext
    ): array {
        if (! $payload instanceof Request) {
            return $properties;
        }

        $this->ensureRequestIsAuthorized($class->name);

        return $properties;
    }

    protected function ensureRequestIsAuthorized(string $class): void
    {
        /** @psalm-suppress UndefinedMethod */
        if (method_exists($class, 'authorize') && app()->call([$class, 'authorize']) === false) {
            throw new AuthorizationException();
        }
    }
}
