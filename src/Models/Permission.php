<?php

namespace OmniGuard\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Carbon;
use OmniGuard\Contracts\Permission as PermissionContract;
use OmniGuard\Exceptions\PermissionAlreadyExists;
use OmniGuard\Exceptions\PermissionDoesNotExist;
use OmniGuard\Guard;
use OmniGuard\PermissionRegistrar;
use OmniGuard\Traits\HasOmniGuard;
use OmniGuard\Traits\RefreshesPermissionCache;

/**
 * @property ?Carbon $created_at
 * @property ?Carbon $updated_at
 */
class Permission extends Model implements PermissionContract
{
    use HasOmniGuard;
    use \OmniGuard\Traits\RefreshesPermissionCache;

    protected $guarded = [];

    public function __construct(array $attributes = [])
    {
        $attributes['guard_name'] ??= Guard::getDefaultName(static::class);

        parent::__construct($attributes);

        $this->guarded[] = $this->primaryKey;
        $this->table = config('omniguard.table_names.permissions', 'omni_permissions');
    }

    protected $casts = [
        'is_system' => 'boolean',
        'last_seen_at' => 'datetime',
        'bit_index' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($permission) {
            if (is_null($permission->bit_index)) {
                $permission->bit_index = static::max('bit_index') + 1;
            }
        });
    }

    /**
     * @return PermissionContract|Permission
     *
     * @throws PermissionAlreadyExists
     */
    public static function create(array $attributes = [])
    {
        $attributes['guard_name'] ??= Guard::getDefaultName(static::class);

        $permission = static::getPermission(['name' => $attributes['name'], 'guard_name' => $attributes['guard_name']]);

        if ($permission) {
            throw PermissionAlreadyExists::create($attributes['name'], $attributes['guard_name']);
        }

        return static::query()->create($attributes);
    }

    /**
     * A permission can be applied to roles.
     */
    public function roles(): BelongsToMany
    {
        $registrar = app(PermissionRegistrar::class);

        return $this->belongsToMany(
            config('omniguard.models.role'),
            config('omniguard.table_names.role_has_permissions'),
            $registrar->pivotPermission,
            $registrar->pivotRole
        );
    }

    /**
     * A permission belongs to some users of the model associated with its guard.
     */
    public function users(): BelongsToMany
    {
        return $this->morphedByMany(
            getModelForGuard($this->attributes['guard_name'] ?? config('auth.defaults.guard')),
            'model',
            config('omniguard.table_names.model_has_permissions'),
            app(PermissionRegistrar::class)->pivotPermission,
            config('omniguard.column_names.model_morph_key')
        );
    }

    /**
     * Find a permission by its name (and optionally guardName).
     *
     * @return PermissionContract|Permission
     *
     * @throws PermissionDoesNotExist
     */
    public static function findByName(string $name, ?string $guardName = null): PermissionContract
    {
        $guardName ??= Guard::getDefaultName(static::class);
        $permission = static::getPermission(['name' => $name, 'guard_name' => $guardName]);
        if (! $permission) {
            throw PermissionDoesNotExist::create($name, $guardName);
        }

        return $permission;
    }

    /**
     * Find a permission by its id (and optionally guardName).
     *
     * @return PermissionContract|Permission
     *
     * @throws PermissionDoesNotExist
     */
    public static function findById(int|string $id, ?string $guardName = null): PermissionContract
    {
        $guardName ??= Guard::getDefaultName(static::class);
        $permission = static::getPermission([(new static)->getKeyName() => $id, 'guard_name' => $guardName]);

        if (! $permission) {
            throw PermissionDoesNotExist::withId($id, $guardName);
        }

        return $permission;
    }

    /**
     * Find or create permission by its name (and optionally guardName).
     *
     * @return PermissionContract|Permission
     */
    public static function findOrCreate(string $name, ?string $guardName = null): PermissionContract
    {
        $guardName ??= Guard::getDefaultName(static::class);
        $permission = static::getPermission(['name' => $name, 'guard_name' => $guardName]);

        if (! $permission) {
            return static::query()->create(['name' => $name, 'guard_name' => $guardName]);
        }

        return $permission;
    }

    /**
     * Get the current cached permissions.
     */
    protected static function getPermissions(array $params = [], bool $onlyOne = false): Collection
    {
        return app(PermissionRegistrar::class)
            ->setPermissionClass(static::class)
            ->getPermissions($params, $onlyOne);
    }

    /**
     * Get the current cached first permission.
     *
     * @return PermissionContract|Permission|null
     */
    protected static function getPermission(array $params = []): ?PermissionContract
    {
        /** @var PermissionContract|null */
        return static::getPermissions($params, true)->first();
    }
}
