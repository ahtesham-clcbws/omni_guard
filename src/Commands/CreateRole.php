<?php

namespace OmniGuard\Commands;

use Illuminate\Console\Command;
use OmniGuard\Contracts\Role;

class CreateRole extends Command
{
    protected $signature = 'omniguard:create-role
        {name : The name of the role}
        {guard? : The name of the guard}';

    protected $description = 'Create a role';

    public function handleLines(): void
    {
        $roleClass = app(Role::class);

        $role = $roleClass::findOrCreate($this->argument('name'), $this->argument('guard'));

        $this->info("Role `{$role->name}` created");
    }

    public function handle()
    {
        $this->handleLines();
    }
}
