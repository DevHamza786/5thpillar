@extends('admin.layouts.app')

@section('title', $definition['label'] ?? $tableKey)

@section('content')
    <h1 class="wp-heading-inline">{{ __($definition['label'] ?? $tableKey) }}</h1>
    <a href="{{ route('admin.cms-tables.index') }}" class="page-title-action">{{ __('← All tables') }}</a>
    <hr class="wp-header-end">

    <p class="description">{{ __($definition['description'] ?? '') }}</p>

    @if ($tableKey === 'fund_prices_archive')
        <form method="get" action="{{ route('admin.cms-tables.edit', $tableKey) }}" class="cms-table-filters tablenav">
            <label for="year">{{ __('Year') }}</label>
            <select id="year" name="year" onchange="this.form.submit()">
                @foreach ($availableYears as $y)
                    <option value="{{ $y }}" @selected($year === (int) $y)>{{ $y }}</option>
                @endforeach
            </select>

            <label for="month">{{ __('Month') }}</label>
            <select id="month" name="month" onchange="this.form.submit()">
                @foreach ($monthsForYear as $monthLabel)
                    <option value="{{ $monthLabel }}" @selected($month === $monthLabel)>{{ $monthLabel }}</option>
                @endforeach
            </select>
        </form>
    @endif

    @if ($rows->isEmpty())
        <div class="notice notice-warning">
            <p>{{ __('No rows in the database for this filter. Run') }} <code>php artisan cms:import-tables fund_prices_archive --force</code> {{ __('or add rows below. The public site falls back to') }} <code>resources/data/fund_price_archives.php</code> {{ __('until data is imported.') }}</p>
        </div>
    @endif

    <form method="post" action="{{ route('admin.cms-tables.update', $tableKey) }}" id="cms-table-editor-form">
        @csrf
        @method('PUT')
        <input type="hidden" name="year" value="{{ $year }}">
        <input type="hidden" name="month" value="{{ $month }}">

        <div class="postbox">
            <h2 class="postbox-header">{{ __('Rows') }} @if($month) <span class="description admin-weight-normal">— {{ $month }} {{ $year }}</span> @endif</h2>
            <div class="inside">
                <p class="admin-flex-row admin-flex-row--mb">
                    <button type="button" class="button" id="cms-table-add-row">{{ __('Add row') }}</button>
                    <button type="button" class="button" id="cms-table-paste-toggle">{{ __('Paste CSV') }}</button>
                </p>

                <div id="cms-table-paste-panel" class="cms-table-paste" hidden>
                    <label for="cms-table-paste-input">{{ __('Paste tab- or comma-separated rows (one per line)') }}</label>
                    <textarea id="cms-table-paste-input" rows="5" placeholder="Date&#9;Agg bid&#9;Agg offer&#9;..."></textarea>
                    <p class="admin-flex-row--mt8">
                        <button type="button" class="button" id="cms-table-paste-apply">{{ __('Apply pasted rows') }}</button>
                    </p>
                </div>

                <div class="cms-table-scroll">
                    <table class="cms-spreadsheet widefat striped" id="cms-spreadsheet">
                        <thead>
                            <tr>
                                @foreach ($columns as $column)
                                    <th>{{ __($column['label']) }}</th>
                                @endforeach
                                <th>{{ __('Enabled') }}</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="cms-spreadsheet-body">
                            @foreach ($rows as $index => $row)
                                <tr data-row>
                                    <input type="hidden" name="rows[{{ $index }}][id]" value="{{ $row->id }}">
                                    @foreach ($columns as $column)
                                        <td>
                                            <input
                                                type="{{ ($column['type'] ?? 'string') === 'integer' ? 'number' : 'text' }}"
                                                name="rows[{{ $index }}][data][{{ $column['key'] }}]"
                                                value="{{ $row->data[$column['key']] ?? '' }}"
                                                @if(($column['type'] ?? '') === 'integer') min="0" @endif
                                            >
                                        </td>
                                    @endforeach
                                    <td class="cms-spreadsheet__check">
                                        <input type="hidden" name="rows[{{ $index }}][is_enabled]" value="0">
                                        <input type="checkbox" name="rows[{{ $index }}][is_enabled]" value="1" @checked($row->is_enabled)>
                                    </td>
                                    <td class="cms-spreadsheet__actions">
                                        <button type="button" class="button button-small" data-row-duplicate>{{ __('Duplicate') }}</button>
                                        <button type="button" class="button button-small button-link-delete" data-row-remove>{{ __('Remove') }}</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <p class="submit admin-submit-row--top">
                    <button type="submit" class="button button-primary">{{ __('Save table') }}</button>
                </p>
            </div>
        </div>
    </form>

    <template id="cms-table-row-template">
        <tr data-row>
            @foreach ($columns as $column)
                <td>
                    <input
                        type="{{ ($column['type'] ?? 'string') === 'integer' ? 'number' : 'text' }}"
                        name="rows[__INDEX__][data][{{ $column['key'] }}]"
                        value="{{ ($column['key'] === 'year' && $year) ? $year : (($column['key'] === 'month' && $month) ? $month : '') }}"
                        @if(($column['type'] ?? '') === 'integer') min="0" @endif
                    >
                </td>
            @endforeach
            <td class="cms-spreadsheet__check">
                <input type="hidden" name="rows[__INDEX__][is_enabled]" value="0">
                <input type="checkbox" name="rows[__INDEX__][is_enabled]" value="1" checked>
            </td>
            <td class="cms-spreadsheet__actions">
                <button type="button" class="button button-small" data-row-duplicate>{{ __('Duplicate') }}</button>
                <button type="button" class="button button-small button-link-delete" data-row-remove>{{ __('Remove') }}</button>
            </td>
        </tr>
    </template>
@endsection

@push('scripts')
<script>
    window.cmsTableEditor = {
        columns: @json($columns),
        year: @json($year),
        month: @json($month),
    };
</script>
<script src="{{ asset('assets/js/admin/cms-table-editor.js') }}?v={{ file_exists(public_path('assets/js/admin/cms-table-editor.js')) ? filemtime(public_path('assets/js/admin/cms-table-editor.js')) : 1 }}"></script>
@endpush
