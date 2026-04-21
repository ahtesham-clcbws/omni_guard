<?php

namespace OmniGuard\Commands;

use Illuminate\Console\Command;
use OmniGuard\Contracts\Role;

class AssignRole extends Command
{
    protected $signature = 'omniguard:assign-role
        {user : The ID of the model}
        {role : The name of the role}
        {guard? : The name of the guard}';

    protected $description = 'Assign a role to a model';

    public function handle()
    {
        $roleClass = app(Role::class);
        $userClass = config('auth.providers.users.model');

        $user = $userClass::findOrFail($this->argument('user'));
        $role = $roleClass::findByName($this->argument('role'), $this->argument('guard'));

        $user->assignRole($role);

        $this->info("Role `{$role->name}` assigned to user `{$user->id}`");
    }
}
