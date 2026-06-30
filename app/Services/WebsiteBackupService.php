<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\Process\Process;
use ZipArchive;

class WebsiteBackupService
{
    public function directory(): string
    {
        return trim(config('site.backup.directory', 'backups'), '/');
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function listBackups(): array
    {
        $disk = Storage::disk(config('site.backup.disk', 'local'));
        $files = collect($disk->files($this->directory()))
            ->filter(fn (string $path) => str_ends_with(strtolower($path), '.zip'))
            ->map(function (string $path) use ($disk) {
                return [
                    'name' => basename($path),
                    'path' => $path,
                    'size' => $disk->size($path),
                    'size_label' => $this->formatBytes($disk->size($path)),
                    'created_at' => date('Y-m-d H:i:s', $disk->lastModified($path)),
                    'timestamp' => $disk->lastModified($path),
                ];
            })
            ->sortByDesc('timestamp')
            ->values()
            ->all();

        return $files;
    }

    /**
     * @return array<string, mixed>
     */
    public function create(): array
    {
        if (! class_exists(ZipArchive::class)) {
            throw new \RuntimeException('PHP Zip extension is required for backups.');
        }

        $disk = Storage::disk(config('site.backup.disk', 'local'));
        $dir = $this->directory();
        $disk->makeDirectory($dir);

        $stamp = now()->format('Y-m-d_His');
        $filename = "website-backup-{$stamp}.zip";
        $relativePath = $dir.'/'.$filename;
        $absolutePath = $disk->path($relativePath);

        $tempDir = storage_path('app/backup-temp/'.Str::uuid());
        File::ensureDirectoryExists($tempDir);

        try {
            $manifest = [
                'app' => config('app.name'),
                'created_at' => now()->toIso8601String(),
                'url' => config('app.url'),
                'laravel' => app()->version(),
                'php' => PHP_VERSION,
            ];

            $this->exportDatabase($tempDir, $manifest);

            foreach (config('site.backup.include_paths', []) as $relative) {
                $source = base_path($relative);
                if (is_dir($source)) {
                    File::copyDirectory($source, $tempDir.'/files/'.str_replace('/', '__', $relative));
                }
            }

            file_put_contents($tempDir.'/manifest.json', json_encode($manifest, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

            $zip = new ZipArchive;
            if ($zip->open($absolutePath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
                throw new \RuntimeException('Could not create backup archive.');
            }

            $this->addFolderToZip($zip, $tempDir, '');
            $zip->close();

            $this->pruneOldBackups();

            return [
                'name' => $filename,
                'path' => $relativePath,
                'size' => $disk->size($relativePath),
                'size_label' => $this->formatBytes($disk->size($relativePath)),
                'created_at' => date('Y-m-d H:i:s'),
            ];
        } finally {
            File::deleteDirectory($tempDir);
        }
    }

    public function delete(string $filename): void
    {
        $safe = basename($filename);
        $path = $this->directory().'/'.$safe;
        $disk = Storage::disk(config('site.backup.disk', 'local'));

        if (! $disk->exists($path)) {
            throw new \RuntimeException('Backup not found.');
        }

        $disk->delete($path);
    }

    public function downloadPath(string $filename): string
    {
        $safe = basename($filename);
        $path = $this->directory().'/'.$safe;
        $disk = Storage::disk(config('site.backup.disk', 'local'));

        if (! $disk->exists($path)) {
            throw new \RuntimeException('Backup not found.');
        }

        return $disk->path($path);
    }

    /**
     * @param  array<string, mixed>  $manifest
     */
    private function exportDatabase(string $tempDir, array &$manifest): void
    {
        $connection = config('database.default');
        $manifest['database_driver'] = $connection;

        if ($connection === 'sqlite') {
            $dbPath = config('database.connections.sqlite.database');
            if (! is_string($dbPath) || ! is_file($dbPath)) {
                throw new \RuntimeException('SQLite database file not found.');
            }

            File::copy($dbPath, $tempDir.'/database.sqlite');
            $manifest['database_file'] = 'database.sqlite';

            return;
        }

        if ($connection === 'mysql' || $connection === 'mariadb') {
            $sqlFile = $tempDir.'/database.sql';
            $this->dumpMysql($sqlFile);
            $manifest['database_file'] = 'database.sql';

            return;
        }

        $jsonFile = $tempDir.'/database-export.json';
        file_put_contents($jsonFile, json_encode($this->exportTablesAsJson(), JSON_PRETTY_PRINT));
        $manifest['database_file'] = 'database-export.json';
    }

    private function dumpMysql(string $outputFile): void
    {
        $cfg = config('database.connections.'.config('database.default'));
        $command = [
            'mysqldump',
            '--host='.($cfg['host'] ?? '127.0.0.1'),
            '--port='.($cfg['port'] ?? '3306'),
            '--user='.($cfg['username'] ?? 'root'),
            '--single-transaction',
            '--routines',
            '--triggers',
            $cfg['database'] ?? '',
        ];

        $process = new Process($command);
        $process->setTimeout(300);
        if (! empty($cfg['password'])) {
            $process->setEnv(['MYSQL_PWD' => (string) $cfg['password']]);
        }

        $process->run();

        if (! $process->isSuccessful()) {
            $json = json_encode($this->exportTablesAsJson(), JSON_PRETTY_PRINT);
            file_put_contents($outputFile, $json);

            return;
        }

        file_put_contents($outputFile, $process->getOutput());
    }

    /**
     * @return array<string, list<array<string, mixed>>>
     */
    private function exportTablesAsJson(): array
    {
        $export = [];
        $connectionName = (string) config('database.default');
        $driver = (string) config("database.connections.{$connectionName}.driver");

        if (in_array($driver, ['mysql', 'mariadb'], true)) {
            $database = (string) config("database.connections.{$connectionName}.database");
            $rows = DB::connection($connectionName)->select(
                'SELECT TABLE_NAME FROM information_schema.TABLES WHERE TABLE_SCHEMA = ? ORDER BY TABLE_NAME',
                [$database]
            );
            $tables = collect($rows)->pluck('TABLE_NAME')->all();
        } else {
            $tables = Schema::connection($connectionName)->getTableListing(schemaQualified: false);
        }

        foreach ($tables as $tableName) {
            if (! is_string($tableName) || $tableName === '') {
                continue;
            }

            $export[$tableName] = DB::connection($connectionName)
                ->table($tableName)
                ->get()
                ->map(fn ($row) => (array) $row)
                ->all();
        }

        return $export;
    }

    private function addFolderToZip(ZipArchive $zip, string $folder, string $base): void
    {
        $items = scandir($folder) ?: [];

        foreach ($items as $item) {
            if ($item === '.' || $item === '..') {
                continue;
            }

            $full = $folder.DIRECTORY_SEPARATOR.$item;
            $local = ltrim($base.'/'.$item, '/');

            if (is_dir($full)) {
                $zip->addEmptyDir($local);
                $this->addFolderToZip($zip, $full, $local);
            } else {
                $zip->addFile($full, $local);
            }
        }
    }

    private function pruneOldBackups(): void
    {
        $retain = (int) config('site.backup.retain_count', 10);
        $backups = $this->listBackups();
        $disk = Storage::disk(config('site.backup.disk', 'local'));

        foreach (array_slice($backups, $retain) as $backup) {
            $disk->delete($backup['path']);
        }
    }

    private function formatBytes(int $bytes): string
    {
        if ($bytes < 1024) {
            return $bytes.' B';
        }

        if ($bytes < 1048576) {
            return round($bytes / 1024, 1).' KB';
        }

        return round($bytes / 1048576, 2).' MB';
    }
}
