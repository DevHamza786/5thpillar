<?php

namespace App\Console\Commands;

use App\Services\WebsiteBackupService;
use Illuminate\Console\Command;

class CreateWebsiteBackupCommand extends Command
{
    protected $signature = 'site:backup';

    protected $description = 'Create a full website backup archive (database + assets)';

    public function handle(WebsiteBackupService $backups): int
    {
        try {
            $result = $backups->create();
        } catch (\Throwable $e) {
            $this->error($e->getMessage());

            return self::FAILURE;
        }

        $this->info(sprintf(
            'Backup created: %s (%s)',
            $result['name'],
            $result['size_label']
        ));

        return self::SUCCESS;
    }
}
