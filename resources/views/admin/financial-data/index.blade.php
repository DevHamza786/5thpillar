@extends('admin.layouts.app')

@section('title', __('Financial Data Management'))

@push('styles')
<style>
    .financial-import { --fi-ink: #1a1f24; --fi-muted: #5c656d; --fi-line: #dfe3e6; --fi-paper: #faf9f7; --fi-accent: #0f766e; --fi-accent-hover: #0d9488; --fi-radius: 12px; font-size: 15px; line-height: 1.5; color: var(--fi-ink); }
    .financial-import * { box-sizing: border-box; }
    
    .fi-card {
        background: #fff; border: 1px solid var(--fi-line); border-radius: var(--fi-radius);
        box-shadow: 0 2px 12px rgba(26,31,36,.06); margin-bottom: 2rem;
    }
    
    .fi-header {
        padding: 1.75rem 1.5rem; border-bottom: 1px solid var(--fi-line);
        background: linear-gradient(145deg, #f7f5f0 0%, #eef6f5 100%);
        border-top-left-radius: var(--fi-radius); border-top-right-radius: var(--fi-radius);
    }
    
    .fi-title { margin: 0; font-family: Georgia, serif; font-size: 1.75rem; font-weight: 400; color: var(--fi-ink); }
    .fi-subtitle { margin: 0.5rem 0 0; color: var(--fi-muted); font-size: 0.95rem; }
    
    .fi-body { padding: 1.5rem; }
    
    .fi-upload-zone {
        border: 2px dashed var(--fi-line); border-radius: 12px; padding: 3rem 2rem;
        text-align: center; transition: all 0.2s ease; cursor: pointer; background: var(--fi-paper);
        position: relative;
    }
    .fi-upload-zone:hover, .fi-upload-zone.dragging { border-color: var(--fi-accent); background: #f0fdfa; }
    
    .fi-upload-icon { font-size: 3rem; color: var(--fi-accent); margin-bottom: 1rem; opacity: 0.7; }
    .fi-upload-text { font-weight: 600; font-size: 1.1rem; margin-bottom: 0.5rem; }
    .fi-upload-hint { color: var(--fi-muted); font-size: 0.9rem; }
    
    .fi-btn {
        display: inline-flex; align-items: center; justify-content: center; padding: 0.75rem 1.5rem;
        border-radius: 8px; font-weight: 600; cursor: pointer; transition: all 0.2s; border: none; font-size: 0.95rem;
    }
    .fi-btn-primary { background: var(--fi-accent); color: #fff; }
    .fi-btn-primary:hover { background: var(--fi-accent-hover); box-shadow: 0 4px 12px rgba(15,118,110,0.2); }
    
    .fi-stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-top: 1.5rem; }
    .fi-stat-item { padding: 1.25rem; border: 1px solid var(--fi-line); border-radius: 10px; background: #fff; }
    .fi-stat-label { font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.05em; color: var(--fi-muted); font-weight: 600; }
    .fi-stat-value { font-size: 1.5rem; font-family: Georgia, serif; color: var(--fi-accent); margin-top: 0.25rem; }
    
    .fi-history-table { width: 100%; border-collapse: collapse; margin-top: 1rem; }
    .fi-history-table th { text-align: left; padding: 1rem; border-bottom: 2px solid var(--fi-line); color: var(--fi-muted); font-size: 0.85rem; text-transform: uppercase; }
    .fi-history-table td { padding: 1rem; border-bottom: 1px solid var(--fi-line); font-size: 0.9rem; }
    .fi-history-table tr:hover { background: #fcfbf9; }
    
    #loading-overlay {
        display: none; position: fixed; inset: 0; background: rgba(255,255,255,0.8);
        z-index: 50; flex-direction: column; align-items: center; justify-content: center; backdrop-filter: blur(4px);
    }
    .fi-spinner {
        width: 50px; height: 50px; border: 5px solid var(--fi-line); border-top-color: var(--fi-accent);
        border-radius: 50%; animation: fi-spin 1s linear infinite; margin-bottom: 1rem;
    }
    @keyframes fi-spin { to { transform: rotate(360deg); } }

    .fi-alert { padding: 1rem 1.5rem; border-radius: 8px; margin-bottom: 1.5rem; display: flex; align-items: flex-start; gap: 1rem; }
    .fi-alert-success { background: #f0fdf4; border: 1px solid #bbf7d0; color: #166534; }
    .fi-alert-error { background: #fef2f2; border: 1px solid #fecaca; color: #991b1b; }
</style>
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
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const dropZone = document.getElementById('drop-zone');
        const fileInput = document.getElementById('file-input');
        const form = document.getElementById('upload-form');
        const submitBtn = document.getElementById('submit-btn');
        const fileSelected = document.getElementById('file-selected');
        const filenameDisplay = document.getElementById('filename-display');
        const loadingOverlay = document.getElementById('loading-overlay');

        // Drag and drop handlers
        dropZone.addEventListener('click', () => fileInput.click());
        
        ['dragenter', 'dragover'].forEach(eventName => {
            dropZone.addEventListener(eventName, (e) => {
                e.preventDefault();
                dropZone.classList.add('dragging');
            }, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, (e) => {
                e.preventDefault();
                dropZone.classList.remove('dragging');
            }, false);
        });

        dropZone.addEventListener('drop', (e) => {
            const dt = e.dataTransfer;
            const files = dt.files;
            fileInput.files = files;
            handleFileSelect();
        });

        fileInput.addEventListener('change', handleFileSelect);

        function handleFileSelect() {
            if (fileInput.files.length > 0) {
                const file = fileInput.files[0];
                filenameDisplay.textContent = file.name;
                fileSelected.classList.remove('hidden');
                submitBtn.disabled = false;
                
                // Visual feedback
                dropZone.classList.add('border-teal-500', 'bg-teal-50');
            }
        }

        form.addEventListener('submit', function() {
            loadingOverlay.style.display = 'flex';
        });
    });
</script>
@endpush
@endsection
