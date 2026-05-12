@extends('admin.layouts.app')

@section('title', __('Financial Data Management'))

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
        <p class="font-bold text-lg text-teal-800">{{ __('Processing Import...') }}</p>
        <p class="text-teal-600 text-sm">{{ __('Please do not close this page.') }}</p>
    </div>

    @if(session('success'))
        <div class="fi-alert fi-alert-success">
            <span class="dashicons dashicons-yes-alt mt-1"></span>
            <div>
                <p class="font-bold text-lg">{{ session('success')['message'] }}</p>
                <div class="mt-1 text-sm opacity-90 grid grid-cols-2 gap-x-8 gap-y-1">
                    <span><strong>{{ __('File:') }}</strong> {{ session('success')['filename'] }}</span>
                    <span><strong>{{ __('Rows Imported:') }}</strong> {{ number_format(session('success')['rows']) }}</span>
                    <span><strong>{{ __('Timestamp:') }}</strong> {{ session('success')['timestamp'] }}</span>
                </div>
            </div>
        </div>
    @endif

    @if($errors->any())
        <div class="fi-alert fi-alert-error">
            <span class="dashicons dashicons-warning mt-1"></span>
            <div>
                <p class="font-bold">{{ __('Upload Failed') }}</p>
                <ul class="mt-1 list-disc list-inside text-sm">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <div class="fi-card">
        <header class="fi-header">
            <h1 class="fi-title">{{ __('Financial Data Import') }}</h1>
            <p class="fi-subtitle">{{ __('Upload your Excel or CSV file to update the Hajj Planner financial data system.') }}</p>
        </header>
        
        <div class="fi-body">
            <form action="{{ route('admin.financial-data.upload') }}" method="POST" enctype="multipart/form-data" id="upload-form">
                @csrf
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-2" for="import-product">{{ __('Product') }}</label>
                    <select name="product" id="import-product" class="w-full max-w-md border border-gray-300 rounded-lg px-3 py-2 text-gray-800 bg-white" required>
                        <option value="hajj">{{ __('Hajj (Haazir workbook, sheet Hajj)') }}</option>
                        <option value="umrah">{{ __('Umrah (Saadat workbook, sheet Format)') }}</option>
                    </select>
                    <p class="text-sm text-gray-500 mt-2 mb-0">{{ __('Import replaces all rows for the selected product only. Use Excel for Umrah; CSV is Hajj only.') }}</p>
                </div>
                <div class="fi-upload-zone" id="drop-zone">
                    <input type="file" name="file" id="file-input" class="hidden" accept=".xlsx,.xls,.csv">
                    <span class="dashicons dashicons-upload fi-upload-icon"></span>
                    <p class="fi-upload-text">{{ __('Click to upload or drag and drop') }}</p>
                    <p class="fi-upload-hint">{{ __('Supports .xlsx, .xls and .csv formats') }}</p>
                    <div id="file-selected" class="mt-4 hidden">
                        <p class="text-teal-700 font-semibold bg-teal-50 py-2 px-4 rounded-full inline-flex items-center">
                            <span class="dashicons dashicons-media-spreadsheet mr-2"></span>
                            <span id="filename-display"></span>
                        </p>
                    </div>
                </div>
                
                <div class="mt-6 flex justify-center">
                    <button type="submit" class="fi-btn fi-btn-primary" id="submit-btn" disabled>
                        <span class="dashicons dashicons-cloud-upload mr-2"></span>
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
                    <div class="fi-stat-label">{{ __('Last File Imported') }}</div>
                    <div class="fi-stat-value text-lg">
                        {{ $lastUpload->filename ?? __('None') }}
                    </div>
                </div>
                <div class="fi-stat-item">
                    <div class="fi-stat-label">{{ __('Last Import Date') }}</div>
                    <div class="fi-stat-value text-lg">
                        {{ $lastUpload ? $lastUpload->created_at->format('M d, Y H:i') : '--' }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($history->count() > 0)
    <div class="fi-card">
        <header class="fi-header !py-4 !bg-none border-b-0">
            <h2 class="fi-title !text-xl">{{ __('Recent Upload History') }}</h2>
        </header>
        <div class="fi-body !pt-0">
            <div class="overflow-x-auto">
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
                            <td class="font-medium">{{ $record->filename }}</td>
                            <td>{{ number_format($record->total_rows) }}</td>
                            <td>{{ $record->uploaded_by }}</td>
                            <td class="text-fi-muted">{{ $record->created_at->format('M d, Y - H:i:s') }}</td>
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
