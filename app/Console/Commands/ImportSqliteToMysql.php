<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ImportSqliteToMysql extends Command
{
    protected $signature = 'db:import-sqlite';

    protected $description = 'Import data from legacy SQLite database into MySQL';

    /** @var list<string> */
    private array $tableOrder = [
        'users',
        'pages',
        'page_sections',
        'cms_media',
        'nav_menu_items',
        'financial_data',
        'financial_data_uploads',
        'hajj_plan_leads',
        'brochure_leads',
        'form_submissions',
        'fund_daily_snapshots',
        'sessions',
        'cache',
        'cache_locks',
        'jobs',
        'job_batches',
        'failed_jobs',
        'password_reset_tokens',
    ];

    public function handle(): int
    {
        if (! is_file(database_path('database.sqlite'))) {
            $this->error('Legacy SQLite file not found at database/database.sqlite');

            return self::FAILURE;
        }

        if (config('database.default') !== 'mysql') {
            $this->error('Default database connection must be mysql. Check DB_CONNECTION in .env');

            return self::FAILURE;
        }

        $sqliteTables = collect(DB::connection('sqlite_legacy')
            ->select("SELECT name FROM sqlite_master WHERE type = 'table' AND name NOT LIKE 'sqlite_%'"))
            ->pluck('name')
            ->all();

        $tables = array_values(array_unique(array_merge(
            $this->tableOrder,
            array_diff($sqliteTables, $this->tableOrder, ['migrations']),
        )));

        DB::connection('mysql')->statement('SET FOREIGN_KEY_CHECKS=0');

        try {
            foreach ($tables as $table) {
                if (! in_array($table, $sqliteTables, true)) {
                    continue;
                }

                if (! Schema::connection('mysql')->hasTable($table)) {
                    $this->warn("Skipping {$table}: table does not exist in MySQL");

                    continue;
                }

                $imported = $this->importTable($table);
                $this->line("Imported {$imported} row(s) into {$table}");
            }
        } finally {
            DB::connection('mysql')->statement('SET FOREIGN_KEY_CHECKS=1');
        }

        $this->info('SQLite data import completed.');

        return self::SUCCESS;
    }

    private function importTable(string $table): int
    {
        $mysql = DB::connection('mysql');
        $sqlite = DB::connection('sqlite_legacy');

        $mysqlColumns = Schema::connection('mysql')->getColumnListing($table);
        $columns = array_values(array_intersect(
            Schema::connection('sqlite_legacy')->getColumnListing($table),
            $mysqlColumns,
        ));

        if ($columns === []) {
            return 0;
        }

        $mysql->table($table)->truncate();

        $rows = $sqlite->table($table)->get();
        if ($rows->isEmpty()) {
            return 0;
        }

        $payload = $rows->map(function ($row) use ($columns) {
            $record = [];
            foreach ($columns as $column) {
                $record[$column] = $row->{$column};
            }

            return $record;
        })->all();

        foreach (array_chunk($payload, 200) as $chunk) {
            $mysql->table($table)->insert($chunk);
        }

        return count($payload);
    }
}
