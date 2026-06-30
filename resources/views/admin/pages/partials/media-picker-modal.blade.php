@php
    $imageFolders = config('cms.media.image_folders', []);
    $pdfFolders = config('cms.media.pdf_folders', []);
@endphp

<div id="cms-media-picker" class="cms-media-picker" hidden aria-hidden="true">
    <div class="cms-media-picker__backdrop" data-media-picker-close></div>
    <div class="cms-media-picker__dialog" role="dialog" aria-modal="true" aria-labelledby="cms-media-picker-title">
        <header class="cms-media-picker__header">
            <h2 id="cms-media-picker-title">{{ __('Media Library') }}</h2>
            <button type="button" class="button" data-media-picker-close>{{ __('Close') }}</button>
        </header>

        <form class="cms-media-picker__upload" data-media-picker-upload enctype="multipart/form-data">
            <div class="cms-media-picker__upload-row">
                <div class="cms-media-picker__upload-field">
                    <label for="cms-media-picker-file">{{ __('Upload file') }}</label>
                    <input id="cms-media-picker-file" type="file" name="file" accept="image/*,application/pdf" required>
                </div>
                <div class="cms-media-picker__upload-field">
                    <label for="cms-media-picker-folder">{{ __('Folder') }}</label>
                    <select id="cms-media-picker-folder" name="folder" required data-media-picker-folder>
                        @foreach ($imageFolders as $path => $label)
                            <option value="{{ $path }}" data-asset-type="image">{{ $label }}</option>
                        @endforeach
                        @foreach ($pdfFolders as $path => $label)
                            <option value="{{ $path }}" data-asset-type="pdf" hidden>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="cms-media-picker__upload-actions">
                    <button type="submit" class="button button-primary" data-media-picker-upload-btn>{{ __('Upload') }}</button>
                </div>
            </div>
            <p class="description cms-media-picker__upload-hint" data-media-picker-upload-status hidden></p>
        </form>

        <div class="cms-media-picker__filters">
            <label for="cms-media-picker-type">{{ __('Type') }}</label>
            <select id="cms-media-picker-type">
                <option value="image">{{ __('Images') }}</option>
                <option value="pdf">{{ __('PDFs') }}</option>
            </select>
        </div>
        <div class="cms-media-picker__grid" data-media-picker-grid>
            <p class="description">{{ __('Loading…') }}</p>
        </div>
    </div>
</div>
