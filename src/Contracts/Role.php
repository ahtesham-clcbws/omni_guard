<?php

namespace OmniGuard\Contracts;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use OmniGuard\Exceptions\RoleDoesNotExist;

/**
 * @property int|string $id
 * @property string $name
 * @property string|null $guard_name
 *
 * @mixin \OmniGuard\Models\Role
 *
 * @phpstan-require-extends \OmniGuard\Models\Role
 */
interface Role
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
     * A role may be given various permissions.
     */
    public function permissions(): BelongsToMany;

    /**
     * A role belongs to many users.
     */
    public function users(): BelongsToMany;

    /**
     * Find a role by its name and guard name.
     *
     *
     * @throws RoleDoesNotExist
     */
    public static function findByName(string $name, ?string $guardName): self;

    /**
     * Find a role by its id and guard name.
     *
     *
     * @throws RoleDoesNotExist
     */
    public static function findById(int|string $id, ?string $guardName): self;

    /**
     * Find or create a role by its name and guard name.
     */
    public static function findOrCreate(string $name, ?string $guardName): self;

     /**
     * Determine if the user may perform the given permission.
     *
     * @param  string|int|Permission|\BackedEnum  $permission
     */
    public function hasPermissionTo($permission, ?string $guardName): bool;

    /**
     * Determine if this role has a higher rank (lower sort_order) than another role.
     */
    public function hasHigherRankThan(Role $role): bool;
}
