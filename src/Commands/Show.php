<?php

namespace OmniGuard\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use OmniGuard\Contracts\Permission;
use OmniGuard\Contracts\Role;

class Show extends Command
{
    protected $signature = 'omniguard:show
            {guard? : The name of the guard}';

    protected $description = 'Show a table of roles and permissions per guard';

    public function handle()
    {
        $permissionClass = app(Permission::class);
        $roleClass = app(Role::class);

        $guard = $this->argument('guard');

        if ($guard) {
            $guards = collect([$guard]);
        } else {
            $guards = app($permissionClass)->newQuery()->pluck('guard_name')
                ->merge(app($roleClass)->newQuery()->pluck('guard_name'))
                ->unique();
        }

        foreach ($guards as $guard) {
            $this->info("Guard: $guard");

            $roles = app($roleClass)->newQuery()->where('guard_name', $guard)->orderBy('name')->get();
            $permissions = app($permissionClass)->newQuery()->where('guard_name', $guard)->orderBy('name')->get();

            $this->displayTable($roles, $permissions);

            $this->line('');
        }
    }

    protected function displayTable(Collection $roles, Collection $permissions)
    {
        $headers = array_merge(['Permission'], $roles->pluck('name')->toArray());

        $body = $permissions->map(function ($permission) use ($roles) {
            return array_merge([$permission->name], $roles->map(function ($role) use ($permission) {
                return $role->hasPermissionTo($permission) ? ' ✔' : '  ·';
            })->toArray());
        });

        $this->table($headers, $body);
    }
}
