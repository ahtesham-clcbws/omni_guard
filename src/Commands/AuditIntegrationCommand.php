<?php

namespace OmniGuard\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class AuditIntegrationCommand extends Command
{
    protected $signature = 'omniguard:audit {--force : Force the addition of missing columns without interaction}';

    protected $description = 'Audit the host system for OmniGuard dynamic integration readiness.';

    public function handle()
    {
        $this->info('Starting OmniGuard Integration Audit...');

        $targets = Config::get('omniguard.models.targets', []);

        if (empty($targets)) {
            $this->warn('No targets defined in config/omniguard.php. Add [\'targets\' => [...]] to your config.');
            return;
        }

        foreach ($targets as $key => $target) {
            $this->info("Auditing target [{$key}] for class [{$target['class']}]...");

            if (!class_exists($target['class'])) {
                $this->error("Target class [{$target['class']}] does not exist.");
                continue;
            }

            $model = new $target['class'];
            $table = $model->getTable();
            $bitmaskColumn = $target['bitmask_column'] ?? 'bit_mask';

            if (!Schema::hasTable($table)) {
                $this->error("Table [{$table}] for model [{$target['class']}] does not exist.");
                continue;
            }

            if (!Schema::hasColumn($table, $bitmaskColumn)) {
                $this->warn("Column [{$bitmaskColumn}] is missing in table [{$table}].");
                if ($this->option('force') || $this->confirm("Do you want to run a migration to add [{$bitmaskColumn}] to [{$table}] now?")) {
                    Schema::table($table, function (Blueprint $table) use ($bitmaskColumn) {
                        $table->unsignedBigInteger($bitmaskColumn)->default(0)->after('id');
                    });
                    $this->info("Added column [{$bitmaskColumn}] to [{$table}].");
                }
            } else {
                $this->info("Target [{$key}] is ready. Column [{$bitmaskColumn}] exists.");
            }
        }

        $this->info('Audit Complete.');
    }
}
