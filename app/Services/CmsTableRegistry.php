<?php

namespace App\Services;

use App\Models\CmsTable;

class CmsTableRegistry
{
    /**
     * @return array<string, array<string, mixed>>
     */
    public function definitions(): array
    {
        return config('cms.tables', []);
    }

    public function has(string $key): bool
    {
        return array_key_exists($key, $this->definitions());
    }

    /**
     * @return array<string, mixed>|null
     */
    public function definition(string $key): ?array
    {
        return $this->definitions()[$key] ?? null;
    }

    /**
     * @return list<array{key: string, name: string, type: string, label: string, required?: bool}>
     */
    public function columns(string $key): array
    {
        return $this->definition($key)['columns'] ?? [];
    }

    public function ensureTable(string $key): CmsTable
    {
        $definition = $this->definition($key);

        if ($definition === null) {
            throw new \InvalidArgumentException("Unknown CMS table key: {$key}");
        }

        return CmsTable::query()->firstOrCreate(
            ['key' => $key],
            [
                'label' => $definition['label'] ?? $key,
                'description' => $definition['description'] ?? null,
                'schema' => ['columns' => $this->columns($key)],
                'settings' => $definition['settings'] ?? [],
            ]
        );
    }

    /**
     * @param  array<string, mixed>  $row
     * @return array<string, mixed>
     */
    public function normalizeRow(string $key, array $row): array
    {
        $normalized = [];

        foreach ($this->columns($key) as $column) {
            $name = $column['key'];
            $value = $row[$name] ?? '';

            if (($column['type'] ?? 'string') === 'integer') {
                $normalized[$name] = (int) $value;
            } else {
                $normalized[$name] = trim((string) $value);
            }
        }

        return $normalized;
    }
}
