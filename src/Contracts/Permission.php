<?php

namespace OmniGuard\Contracts;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use OmniGuard\Exceptions\PermissionDoesNotExist;

/**
 * @property int|string $id
 * @property string $name
 * @property string|null $guard_name
 *
 * @mixin \OmniGuard\Models\Permission
 *
 * @phpstan-require-extends \OmniGuard\Models\Permission
 */
interface Permission
{
    /**
     * Get the value of the model's primary key.
     */
    public function getKey(): mixed;

    /**
     * Get the primary key for the model.
     */
    public function getKeyName(): string;
    /**
     * A permission can be applied to roles.
     */
    public function roles(): BelongsToMany;

    /**
     * A permission can be applied directly to users.
     */
    public function users(): BelongsToMany;

    /**
     * Find a permission by its name.
     *
     *
     * @throws PermissionDoesNotExist
     */
    public static function findByName(string $name, ?string $guardName): self;

    /**
     * Find a permission by its id.
     *
     *
     * @throws PermissionDoesNotExist
     */
    public static function findById(int|string $id, ?string $guardName): self;

    /**
     * Find or Create a permission by its name and guard name.
     */
    public static function findOrCreate(string $name, ?string $guardName): self;
}
