<?php

namespace OmniGuard\Engine;

use Illuminate\Support\Str;

class HeuristicMapper
{
    /**
     * The core synonym mapping for verbs to resource types.
     */
    protected array $synonymMap = [
        'view' => ['view', 'show', 'index', 'get', 'read', 'see', 'display', 'list'],
        'create' => ['create', 'add', 'store', 'new', 'post', 'insert'],
        'update' => ['update', 'edit', 'patch', 'modify', 'save', 'change'],
        'delete' => ['delete', 'remove', 'destroy', 'trash', 'prune', 'wipe'],
        'force_delete' => ['force', 'permanent', 'purge'],
        'restore' => ['restore', 'undelete', 'recover'],
    ];

    /**
     * Map a raw codebase string (e.g. method name) to a permission slug.
     */
    public function map(string $raw, ?string $resource = null): string
    {
        $raw = Str::snake($raw);
        $parts = explode('_', $raw);
        
        $detectedAction = 'view'; // Default
        
        foreach ($parts as $part) {
            foreach ($this->synonymMap as $canonical => $synonyms) {
                if (in_array($part, $synonyms)) {
                    $detectedAction = $canonical;
                    break 2;
                }
            }
        }

        if ($resource) {
            $resource = Str::snake($resource);
            return "{$resource}.{$detectedAction}";
        }

        return $detectedAction;
    }
}
