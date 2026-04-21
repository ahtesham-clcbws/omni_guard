<?php

namespace OmniGuard\Commands;

use Illuminate\Console\Command;
use OmniGuard\Contracts\Permission;

class CreatePermission extends Command
{
    protected $signature = 'omniguard:create-permission
        {name : The name of the permission}
        {guard? : The name of the guard}';

    protected $description = 'Create a permission';

    public function handleLines(): void
    {
        $permissionClass = app(Permission::class);

        $permission = $permissionClass::findOrCreate($this->argument('name'), $this->argument('guard'));

        $this->info("Permission `{$permission->name}` created");
    }

    public function handle()
    {
        $this->handleLines();
    }
}
