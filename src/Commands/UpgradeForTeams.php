<?php

namespace OmniGuard\Commands;

use Illuminate\Console\Command;
use OmniGuard\Contracts\Permission;
use OmniGuard\Contracts\Role;

class UpgradeForTeams extends Command
{
    protected $signature = 'omniguard:upgrade-teams';

    protected $description = 'Upgrade existing roles and permissions for the teams feature';

    public function handle()
    {
        if (!config('omniguard.teams')) {
            $this->error('Teams are not enabled in config/omniguard.php. Please enable them before running this command.');
            return;
        }

        $this->info('Upgrading existing roles and permissions for teams...');

        $roleClass = app(Role::class);
        $permissionClass = app(Permission::class);

        $teamKey = config('omniguard.column_names.team_foreign_key', 'team_id');

        app($roleClass)->newQuery()->whereNull($teamKey)->update([$teamKey => null]);
        app($permissionClass)->newQuery()->whereNull($teamKey)->update([$teamKey => null]);

        $this->info('Complete. All existing roles and permissions are now globally scoped (team_id is null).');
    }
}
