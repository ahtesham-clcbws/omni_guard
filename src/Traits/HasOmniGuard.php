<?php

namespace OmniGuard\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use OmniGuard\Contracts\Permission;
use OmniGuard\Contracts\Role;
use OmniGuard\Events\RoleAttached;
use OmniGuard\Events\RoleDetached;
use OmniGuard\PermissionRegistrar;

/**
 * @property-read int|string $id
 * @property string $email
 * @method mixed getKey()
 * @method string getKeyName()
 * @method \Illuminate\Database\Eloquent\Relations\BelongsToMany users()
 * @method \Illuminate\Database\Eloquent\Relations\BelongsToMany roles()
 * @method \Illuminate\Database\Eloquent\Relations\BelongsToMany permissions()
 * @method \Illuminate\Database\Eloquent\Model getModel()
 * @method \Illuminate\Database\Eloquent\Model load(array|string $relations)
 * @method \Illuminate\Database\Eloquent\Model loadMissing(array|string $relations)
 * @method \Illuminate\Database\Eloquent\Relations\BelongsToMany morphToMany(string $related, string $name, string $table = null, string $foreignPivotKey = null, string $relatedPivotKey = null, string $parentKey = null, string $relatedKey = null, bool $inverse = false)
 * @mixin \Illuminate\Database\Eloquent\Model
 */
trait HasOmniGuard
{
    use HasPermissions;

    /**
     * Get the highest ranking role for this user.
     * Higher rank = lower sort_order.
     */
    public function getTopRole(): ?Role
    {
        return $this->roles()->orderBy('sort_order', 'asc')->first();
    }

    /**
     * Get the user's email for SuperAdmin verification.
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    private ?string $roleClass = null;

    public static function bootHasOmniGuard()
    {
        static::deleting(function ($model) {
            if (method_exists($model, 'isForceDeleting') && ! $model->isForceDeleting()) {
                return;
            }

            $teams = app(PermissionRegistrar::class)->teams;
            app(PermissionRegistrar::class)->teams = false;
            
            if ($model instanceof \OmniGuard\Contracts\Role) {
                $model->permissions()->detach();
                $model->users()->detach();
            }

            if ($model instanceof \OmniGuard\Contracts\Permission) {
                $model->roles()->detach();
                $model->users()->detach();
            }
            
            app(PermissionRegistrar::class)->teams = $teams;
        });
    }

    public function getRoleClass(): string
    {
        if (! $this->roleClass) {
            $this->roleClass = app(PermissionRegistrar::class)->getRoleClass();
        }

        return $this->roleClass;
    }

    /**
     * A model may have multiple roles.
     */
    public function roles(): BelongsToMany
    {
        /** @var \Illuminate\Database\Eloquent\Model $this */
        $relation = $this->morphToMany(
            config('omniguard.models.role'),
            'model',
            config('omniguard.table_names.model_has_roles'),
            config('omniguard.column_names.model_morph_key'),
            app(PermissionRegistrar::class)->pivotRole
        );

        if (! app(PermissionRegistrar::class)->teams) {
            return $relation;
        }

        $teamsKey = app(PermissionRegistrar::class)->teamsKey;
        $relation->withPivot($teamsKey);
        $teamField = config('omniguard.table_names.roles').'.'.$teamsKey;

        return $relation->wherePivot($teamsKey, getPermissionsTeamId())
            ->where(fn ($q) => $q->whereNull($teamField)->orWhere($teamField, getPermissionsTeamId()));
    }

    /**
     * Scope the model query to certain roles only.
     */
    public function scopeRole(Builder $query, $roles, $guard = null, $without = false): Builder
    {
        if ($roles instanceof Collection) {
            $roles = $roles->all();
        }

        $roles = array_map(function ($role) use ($guard) {
            if ($role instanceof Role) {
                return $role;
            }

            if ($role instanceof \BackedEnum) {
                $role = $role->value;
            }

            $method = is_int($role) || PermissionRegistrar::isUid($role) ? 'findById' : 'findByName';

            return $this->getRoleClass()::{$method}($role, $guard ?: $this->getDefaultGuardName());
        }, Arr::wrap($roles));

        $key = (new ($this->getRoleClass())())->getKeyName();

        return $query->{! $without ? 'whereHas' : 'whereDoesntHave'}('roles', fn (Builder $subQuery) => $subQuery
            ->whereIn(config('omniguard.table_names.roles').".$key", \array_column($roles, $key))
        );
    }

    /**
     * Scope the model query to only those without certain roles.
     */
    public function scopeWithoutRole(Builder $query, $roles, $guard = null): Builder
    {
        return $this->scopeRole($query, $roles, $guard, true);
    }

    /**
     * Returns array of role ids
     */
    private function collectRoles(...$roles): array
    {
        return collect($roles)
            ->flatten()
            ->reduce(function ($array, $role) {
                if (empty($role)) {
                    return $array;
                }

                $role = $this->getStoredRole($role);

                if (! in_array($role->getKey(), $array)) {
                    $this->ensureModelSharesGuard($role);
                    $array[] = $role->getKey();
                }

                return $array;
            }, []);
    }

    /**
     * Assign the given role to the model.
     */
    public function assignRole(...$roles)
    {
        $roles = $this->collectRoles($roles);

        /** @var \Illuminate\Database\Eloquent\Model $model */
        $model = $this->getModel();
        $teamPivot = app(PermissionRegistrar::class)->teams && ! is_a($this, Permission::class) ?
            [app(PermissionRegistrar::class)->teamsKey => getPermissionsTeamId()] : [];

        if ($model->exists) {
            if (app(PermissionRegistrar::class)->teams) {
                $this->load('roles');
            }

            $currentRoles = $this->roles->map(fn ($role) => $role->getKey())->toArray();

            $this->roles()->attach(array_diff($roles, $currentRoles), $teamPivot);
            $model->unsetRelation('roles');
        } else {
            $class = \get_class($model);
            $saved = false;

            $class::saved(
                function ($object) use ($roles, $model, $teamPivot, &$saved) {
                    if ($saved || $model->getKey() != $object->getKey()) {
                        return;
                    }
                    $model->roles()->attach($roles, $teamPivot);
                    $model->unsetRelation('roles');
                    $saved = true;
                }
            );
        }

        if (is_a($this, Permission::class)) {
            $this->forgetCachedPermissions();
        }

        if (config('omniguard.events_enabled') ?? true) {
            event(new RoleAttached($this->getModel(), $roles));
        }

        return $this;
    }

    /**
     * Revoke the given role from the model.
     */
    public function removeRole(...$role)
    {
        $roles = $this->collectRoles($role);

        $this->roles()->detach($roles);

        $this->unsetRelation('roles');

        if (is_a($this, Permission::class)) {
            $this->forgetCachedPermissions();
        }

        if (config('omniguard.events_enabled') ?? true) {
            event(new RoleDetached($this->getModel(), $roles));
        }

        return $this;
    }

    /**
     * Remove all current roles and set the given ones.
     */
    public function syncRoles(...$roles)
    {
        /** @var \Illuminate\Database\Eloquent\Model $this */
        if ($this->exists) {
            $this->collectRoles($roles);
            if (config('omniguard.events_enabled') ?? true) {
                $currentRoles = $this->roles()->get();
                if ($currentRoles->isNotEmpty()) {
                    $this->removeRole($currentRoles);
                }
            } else {
                $this->roles()->detach();
                $this->setRelation('roles', collect());
            }
        }

        return $this->assignRole($roles);
    }

    /**
     * Determine if the model has (one of) the given role(s).
     */
    public function hasRole($roles, ?string $guard = null): bool
    {
        $this->loadMissing('roles');

        if (is_string($roles) && strpos($roles, '|') !== false) {
            $roles = $this->convertPipeToArray($roles);
        }

        if ($roles instanceof \BackedEnum) {
            $roles = $roles->value;

            return $this->roles
                ->when($guard, fn ($q) => $q->where('guard_name', $guard))
                ->pluck('name')
                ->contains(function ($name) use ($roles) {
                    if ($name instanceof \BackedEnum) {
                        return $name->value == $roles;
                    }

                    return $name == $roles;
                });
        }

        if (is_int($roles) || PermissionRegistrar::isUid($roles)) {
            $key = (new ($this->getRoleClass())())->getKeyName();

            return $guard
                ? $this->roles->where('guard_name', $guard)->contains($key, $roles)
                : $this->roles->contains($key, $roles);
        }

        if (is_string($roles)) {
            return $guard
                ? $this->roles->where('guard_name', $guard)->contains('name', $roles)
                : $this->roles->contains('name', $roles);
        }

        if ($roles instanceof Role) {
            return $this->roles->contains($roles->getKeyName(), $roles->getKey());
        }

        if (is_array($roles)) {
            foreach ($roles as $role) {
                if ($this->hasRole($role, $guard)) {
                    return true;
                }
            }

            return false;
        }

        if ($roles instanceof Collection) {
            return $roles->intersect($guard ? $this->roles->where('guard_name', $guard) : $this->roles)->isNotEmpty();
        }

        throw new \TypeError('Unsupported type for $roles parameter to hasRole().');
    }

    /**
     * Determine if the model has any of the given role(s).
     */
    public function hasAnyRole(...$roles): bool
    {
        return $this->hasRole($roles);
    }

    /**
     * Determine if the model has all of the given role(s).
     */
    public function hasAllRoles($roles, ?string $guard = null): bool
    {
        $this->loadMissing('roles');

        if ($roles instanceof \BackedEnum) {
            $roles = $roles->value;
        }

        if (is_string($roles) && strpos($roles, '|') !== false) {
            $roles = $this->convertPipeToArray($roles);
        }

        if (is_string($roles)) {
            return $this->hasRole($roles, $guard);
        }

        if ($roles instanceof Role) {
            return $this->roles->contains($roles->getKeyName(), $roles->getKey());
        }

        $roles = collect()->make($roles)->map(function ($role) {
            if ($role instanceof \BackedEnum) {
                return $role->value;
            }

            return $role instanceof Role ? $role->name : $role;
        });

        $roleNames = $guard
            ? $this->roles->where('guard_name', $guard)->pluck('name')
            : $this->getRoleNames();

        $roleNames = $roleNames->transform(function ($roleName) {
            if ($roleName instanceof \BackedEnum) {
                return $roleName->value;
            }

            return $roleName;
        });

        return $roles->intersect($roleNames) == $roles;
    }

    /**
     * Determine if the model has exactly all of the given role(s).
     */
    public function hasExactRoles($roles, ?string $guard = null): bool
    {
        $this->loadMissing('roles');

        if (is_string($roles) && strpos($roles, '|') !== false) {
            $roles = $this->convertPipeToArray($roles);
        }

        if (is_string($roles)) {
            $roles = [$roles];
        }

        if ($roles instanceof Role) {
            $roles = [$roles->name];
        }

        $roles = collect()->make($roles)->map(fn ($role) => $role instanceof Role ? $role->name : $role
        );

        return $this->roles->count() == $roles->count() && $this->hasAllRoles($roles, $guard);
    }

    /**
     * Return all permissions directly coupled to the model.
     */
    public function getDirectPermissions(): Collection
    {
        return $this->permissions;
    }

    public function getRoleNames(): Collection
    {
        $this->loadMissing('roles');

        return $this->roles->pluck('name');
    }

    protected function getStoredRole($role): Role
    {
        if ($role instanceof \BackedEnum) {
            $role = $role->value;
        }

        if (is_int($role) || PermissionRegistrar::isUid($role)) {
            return $this->getRoleClass()::findById($role, $this->getDefaultGuardName());
        }

        if (is_string($role)) {
            return $this->getRoleClass()::findByName($role, $this->getDefaultGuardName());
        }

        return $role;
    }

    protected function convertPipeToArray(string $pipeString)
    {
        $pipeString = trim($pipeString);

        if (strlen($pipeString) <= 2) {
            return [str_replace('|', '', $pipeString)];
        }

        $quoteCharacter = substr($pipeString, 0, 1);
        $endCharacter = substr($quoteCharacter, -1, 1);

        if ($quoteCharacter !== $endCharacter) {
            return explode('|', $pipeString);
        }

        if (! in_array($quoteCharacter, ["'", '"'])) {
            return explode('|', $pipeString);
        }

        return explode('|', trim($pipeString, $quoteCharacter));
    }
}
