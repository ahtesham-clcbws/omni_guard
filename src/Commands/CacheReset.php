<?php

namespace OmniGuard\Commands;

use Illuminate\Console\Command;
use OmniGuard\PermissionRegistrar;

class CacheReset extends Command
{
    protected $signature = 'omniguard:cache-reset';

    protected $description = 'Reset the OmniGuard permission cache';

    public function handleLines(): void
    {
        if (app(PermissionRegistrar::class)->forgetCachedPermissions()) {
            $this->info('OmniGuard permission cache flushed.');
        } else {
            $this->error('Failed to flush OmniGuard permission cache.');
        }
    }

    public function handle()
    {
        $this->handleLines();
    }
}
