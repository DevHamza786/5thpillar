<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CmsTable;
use App\Models\CmsTableRow;
use App\Services\CmsTableRegistry;
use App\Services\FundPriceArchiveRepository;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CmsTableController extends Controller
{
    public function __construct(
        private readonly CmsTableRegistry $registry
    ) {}

    public function index(): View
    {
        $definitions = $this->registry->definitions();
        $tables = [];

        foreach ($definitions as $key => $definition) {
            $table = CmsTable::query()->where('key', $key)->first();
            $tables[] = [
                'key' => $key,
                'label' => $definition['label'] ?? $key,
                'description' => $definition['description'] ?? '',
                'row_count' => $table?->rows()->count() ?? 0,
            ];
        }

        return view('admin.cms-tables.index', [
            'tables' => $tables,
        ]);
    }

    public function edit(Request $request, string $tableKey): View
    {
        if (! $this->registry->has($tableKey)) {
            abort(404);
        }

        $cmsTable = $this->registry->ensureTable($tableKey);
        $columns = $this->registry->columns($tableKey);
        $definition = $this->registry->definition($tableKey);

        $year = (int) $request->query('year', 0);
        $month = trim((string) $request->query('month', ''));

        $availableYears = $tableKey === 'fund_prices_archive'
            ? FundPriceArchiveRepository::availableYears()
            : [];

        if ($year === 0 && $availableYears !== []) {
            $year = (int) max($availableYears);
        }

        $monthsForYear = [];
        if ($tableKey === 'fund_prices_archive' && $year > 0) {
            $monthsForYear = array_keys(FundPriceArchiveRepository::monthsForYear($year));
        }

        if ($month === '' && $monthsForYear !== []) {
            $month = (string) $monthsForYear[0];
        }

        $query = $cmsTable->rows()->orderBy('sort_order');

        if ($tableKey === 'fund_prices_archive') {
            if ($year > 0) {
                $query->where('data->year', $year);
            }
            if ($month !== '') {
                $query->where('data->month', $month);
            }
        }

        $rows = $query->get();

        return view('admin.cms-tables.edit', [
            'tableKey' => $tableKey,
            'cmsTable' => $cmsTable,
            'definition' => $definition,
            'columns' => $columns,
            'rows' => $rows,
            'year' => $year,
            'month' => $month,
            'availableYears' => $availableYears,
            'monthsForYear' => $monthsForYear,
        ]);
    }

    public function update(Request $request, string $tableKey): RedirectResponse
    {
        if (! $this->registry->has($tableKey)) {
            abort(404);
        }

        $cmsTable = $this->registry->ensureTable($tableKey);

        $validated = $request->validate([
            'year' => ['nullable', 'integer'],
            'month' => ['nullable', 'string', 'max:120'],
            'rows' => ['nullable', 'array'],
            'rows.*.id' => ['nullable', 'integer'],
            'rows.*.data' => ['nullable', 'array'],
            'rows.*.is_enabled' => ['nullable', 'boolean'],
        ]);

        $filterYear = isset($validated['year']) ? (int) $validated['year'] : null;
        $filterMonth = isset($validated['month']) ? trim((string) $validated['month']) : null;

        $incomingRows = $validated['rows'] ?? [];
        $keptIds = [];

        foreach ($incomingRows as $index => $payload) {
            $data = $this->registry->normalizeRow($tableKey, $payload['data'] ?? []);

            if ($tableKey === 'fund_prices_archive') {
                if ($data['year'] === 0 && $filterYear) {
                    $data['year'] = $filterYear;
                }
                if ($data['month'] === '' && $filterMonth) {
                    $data['month'] = $filterMonth;
                }
            }

            if ($tableKey === 'fund_prices_archive' && ($data['date'] === '' || $data['year'] === 0 || $data['month'] === '')) {
                continue;
            }

            $rowId = isset($payload['id']) ? (int) $payload['id'] : null;

            if ($rowId) {
                $row = CmsTableRow::query()
                    ->where('cms_table_id', $cmsTable->id)
                    ->where('id', $rowId)
                    ->first();

                if ($row !== null) {
                    $row->update([
                        'sort_order' => $index,
                        'data' => $data,
                        'is_enabled' => ! empty($payload['is_enabled']),
                    ]);
                    $keptIds[] = $row->id;
                }

                continue;
            }

            $created = $cmsTable->rows()->create([
                'sort_order' => $index,
                'data' => $data,
                'is_enabled' => ! empty($payload['is_enabled']),
            ]);
            $keptIds[] = $created->id;
        }

        if ($tableKey === 'fund_prices_archive' && $filterYear && $filterMonth) {
            $cmsTable->rows()
                ->where('data->year', $filterYear)
                ->where('data->month', $filterMonth)
                ->whereNotIn('id', $keptIds)
                ->delete();
        } else {
            $cmsTable->rows()->whereNotIn('id', $keptIds)->delete();
        }

        $redirectParams = array_filter([
            'year' => $filterYear,
            'month' => $filterMonth,
        ]);

        return redirect()
            ->route('admin.cms-tables.edit', array_merge(['tableKey' => $tableKey], $redirectParams))
            ->with('status', __('Table rows saved.'));
    }
}
