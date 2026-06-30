@extends('admin.layouts.app')

@section('title', __('Hajj & Umrah planner data'))

@push('styles')
    @php
        $__fiCss = public_path('assets/css/admin/admin-financial-data.css');
        $__fiCssVer = is_file($__fiCss) ? (string) filemtime($__fiCss) : '1';
    @endphp
    <link rel="stylesheet" href="{{ asset('assets/css/admin/admin-financial-data.css') }}?v={{ $__fiCssVer }}">
@endpush

@section('content')
<div class="financial-import">
    <div id="loading-overlay">
        <div class="fi-spinner"></div>
        <p class="fi-overlay-title">{{ __('Processing Import...') }}</p>
        <p class="fi-overlay-hint">{{ __('Please do not close this page.') }}</p>
    </div>

    @if(session('success'))
        <div class="fi-alert fi-alert-success">
            <span class="dashicons dashicons-yes-alt" aria-hidden="true"></span>
            <div>
                <p class="fi-alert-title">{{ session('success')['message'] }}</p>
                <div class="fi-alert-meta">
                    <span><strong>{{ __('File:') }}</strong> {{ session('success')['filename'] }}</span>
                    <span><strong>{{ __('Rows Imported:') }}</strong> {{ number_format(session('success')['rows']) }}</span>
                    <span><strong>{{ __('Timestamp:') }}</strong> {{ session('success')['timestamp'] }}</span>
                </div>
            </div>
        </div>
    @endif

    @if($errors->any())
        <div class="fi-alert fi-alert-error">
            <span class="dashicons dashicons-warning" aria-hidden="true"></span>
            <div>
                <p class="fi-alert-title">{{ __('Upload Failed') }}</p>
                <ul class="fi-alert-list">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <div class="fi-card">
        <header class="fi-header">
            <h1 class="fi-title">{{ __('Hajj & Umrah planner data') }}</h1>
            <p class="fi-subtitle">{{ __('Import Excel or CSV workbooks and export current planner tables for Hajj and Umrah.') }}</p>
        </header>

        <div class="fi-body">
            <form action="{{ route('admin.financial-data.upload') }}" method="POST" enctype="multipart/form-data" id="upload-form">
                @csrf
                <div class="fi-field">
                    <label class="fi-field-label" for="import-product">{{ __('Product') }}</label>
                    <select name="product" id="import-product" class="fi-field-select" required>
                        <option value="hajj">{{ __('Hajj (Haazir workbook, sheet Hajj)') }}</option>
                        <option value="umrah">{{ __('Umrah (Saadat workbook, sheet Format)') }}</option>
                    </select>
                    <p class="fi-field-hint">{{ __('Import replaces all rows for the selected product only. Use Excel for Umrah; CSV is Hajj only.') }}</p>
                </div>
                <div class="fi-upload-zone" id="drop-zone">
                    <input type="file" name="file" id="file-input" class="fi-file-input" accept=".xlsx,.xls,.csv">
                    <span class="dashicons dashicons-upload fi-upload-icon" aria-hidden="true"></span>
                    <p class="fi-upload-text">{{ __('Click to upload or drag and drop') }}</p>
                    <p class="fi-upload-hint">{{ __('Supports .xlsx, .xls and .csv formats') }}</p>
                    <div id="file-selected" class="fi-file-selected is-hidden">
                        <p class="fi-file-badge">
                            <span class="dashicons dashicons-media-spreadsheet" aria-hidden="true"></span>
                            <span id="filename-display"></span>
                        </p>
                    </div>
                </div>

                <div class="fi-actions">
                    <button type="submit" class="fi-btn fi-btn-primary" id="submit-btn" disabled>
                        <span class="dashicons dashicons-cloud-upload" aria-hidden="true"></span>
                        {{ __('Start Import Process') }}
                    </button>
                </div>
            </form>

            <div class="fi-stats">
                <div class="fi-stat-item">
                    <div class="fi-stat-label">{{ __('Total Active Records') }}</div>
                    <div class="fi-stat-value">{{ number_format($totalDataRows) }}</div>
                </div>
                <div class="fi-stat-item">
                    <div class="fi-stat-label">{{ __('Hajj rows') }}</div>
                    <div class="fi-stat-value">{{ number_format($hajjDataRows) }}</div>
                </div>
                <div class="fi-stat-item">
                    <div class="fi-stat-label">{{ __('Umrah rows') }}</div>
                    <div class="fi-stat-value">{{ number_format($umrahDataRows) }}</div>
                </div>
                <div class="fi-stat-item">
                    <div class="fi-stat-label">{{ __('Last File Imported') }}</div>
                    <div class="fi-stat-value fi-stat-value--sm">
                        {{ $lastUpload->filename ?? __('None') }}
                    </div>
                </div>
                <div class="fi-stat-item">
                    <div class="fi-stat-label">{{ __('Last Import Date') }}</div>
                    <div class="fi-stat-value fi-stat-value--sm">
                        {{ $lastUpload ? $lastUpload->created_at->format('M d, Y H:i') : '--' }}
                    </div>
                </div>
            </div>

            <div class="fi-actions fi-actions--row">
                <a class="fi-btn fi-btn-primary" href="{{ route('admin.financial-data.export', ['product' => 'hajj']) }}">
                    <span class="dashicons dashicons-download" aria-hidden="true"></span>
                    {{ __('Export Hajj CSV') }}
                </a>
                <a class="fi-btn fi-btn-primary" href="{{ route('admin.financial-data.export', ['product' => 'umrah']) }}">
                    <span class="dashicons dashicons-download" aria-hidden="true"></span>
                    {{ __('Export Umrah CSV') }}
                </a>
            </div>
        </div>
    </div>

    @if($history->count() > 0)
    <div class="fi-card">
        <header class="fi-header fi-header--compact">
            <h2 class="fi-title fi-title--sm">{{ __('Recent Upload History') }}</h2>
        </header>
        <div class="fi-body fi-body--flush">
            <div class="fi-table-wrap">
                <table class="fi-history-table">
                    <thead>
                        <tr>
                            <th>{{ __('Filename') }}</th>
                            <th>{{ __('Imported Rows') }}</th>
                            <th>{{ __('Uploaded By') }}</th>
                            <th>{{ __('Date & Time') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($history as $record)
                        <tr>
                            <td class="fi-text-medium">{{ $record->filename }}</td>
                            <td>{{ number_format($record->total_rows) }}</td>
                            <td>{{ $record->uploaded_by }}</td>
                            <td class="fi-text-muted">{{ $record->created_at->format('M d, Y - H:i:s') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif
</div>

@push('scripts')
    @php
        $__fiJs = public_path('assets/js/admin/admin-financial-upload.js');
        $__fiJsVer = is_file($__fiJs) ? (string) filemtime($__fiJs) : '1';
    @endphp
    <script src="{{ asset('assets/js/admin/admin-financial-upload.js') }}?v={{ $__fiJsVer }}" defer></script>
@endpush
@endsection
