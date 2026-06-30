@php
    $isEn = $locale === 'en';
    $rows = old('content.rows', $content['rows'] ?? []);
    if (! is_array($rows)) {
        $rows = [];
    }
@endphp
<div class="cms-section-fields cms-section-fields--pdf-table" data-section-fields="pdf_table" @if($currentType !== 'pdf_table') hidden @endif>
    @if ($isEn)
        <div class="row cols-2">
            <div>
                <label>{{ __('Table title') }}</label>
                <input type="text" name="content[title]" value="{{ old('content.title', $content['title'] ?? '') }}" class="large-text">
            </div>
            <div>
                <label>{{ __('First column header') }}</label>
                <input type="text" name="content[column_label]" value="{{ old('content.column_label', $content['column_label'] ?? 'Document') }}">
            </div>
        </div>
        <div class="row cols-2">
            <div>
                <label>{{ __('Download link text') }}</label>
                <input type="text" name="content[download_label]" value="{{ old('content.download_label', $content['download_label'] ?? 'Click Here') }}">
            </div>
            <div>
                <label>{{ __('Wrapper CSS class') }}</label>
                <input type="text" name="settings[wrapper_class]" value="{{ old('settings.wrapper_class', $settings['wrapper_class'] ?? 'laravel-financial-statements-page') }}">
            </div>
        </div>
    @else
        <div class="row cols-2">
            <div>
                <label>{{ __('Table title (Urdu)') }}</label>
                <input type="text" name="content[title_ur]" value="{{ old('content.title_ur', $content['title_ur'] ?? '') }}" class="large-text" dir="rtl">
            </div>
            <div>
                <label>{{ __('First column header (Urdu)') }}</label>
                <input type="text" name="content[column_label_ur]" value="{{ old('content.column_label_ur', $content['column_label_ur'] ?? '') }}" dir="rtl">
            </div>
        </div>
        <div class="row">
            <div>
                <label>{{ __('Download link text (Urdu)') }}</label>
                <input type="text" name="content[download_label_ur]" value="{{ old('content.download_label_ur', $content['download_label_ur'] ?? '') }}" dir="rtl">
            </div>
        </div>
    @endif

    @if ($isEn)
        <h4 class="admin-heading-sm--spaced">{{ __('PDF rows') }}</h4>
        <div class="cms-pdf-rows" data-pdf-rows>
            @foreach ($rows as $index => $row)
                <div class="cms-pdf-row" data-pdf-row>
                    <div class="row cols-2">
                        <div>
                            <label>{{ __('Label') }}</label>
                            <input type="text" name="content[rows][{{ $index }}][label]" value="{{ $row['label'] ?? '' }}">
                        </div>
                        <div>
                            <label>{{ __('Label (Urdu)') }}</label>
                            <input type="text" name="content[rows][{{ $index }}][label_ur]" value="{{ $row['label_ur'] ?? '' }}" dir="rtl">
                        </div>
                    </div>
                    <div class="row">
                        <div>
                            <label>{{ __('PDF path') }}</label>
                            <div class="cms-url-copy">
                                <input type="text" name="content[rows][{{ $index }}][path]" value="{{ $row['path'] ?? '' }}" class="large-text cms-media-path-input" placeholder="assets/pdf/…">
                                <button type="button" class="button button-small cms-media-pick-btn" data-media-type="pdf">{{ __('Pick PDF') }}</button>
                            </div>
                        </div>
                    </div>
                    <p class="admin-paragraph-mb">
                        <button type="button" class="button button-small button-link-delete" data-pdf-row-remove>{{ __('Remove row') }}</button>
                    </p>
                </div>
            @endforeach
        </div>
        <p class="admin-submit-row">
            <button type="button" class="button" data-pdf-row-add>{{ __('Add PDF row') }}</button>
        </p>
        <template id="cms-pdf-row-template">
            <div class="cms-pdf-row" data-pdf-row>
                <div class="row cols-2">
                    <div>
                        <label>{{ __('Label') }}</label>
                        <input type="text" name="content[rows][__INDEX__][label]" value="">
                    </div>
                    <div>
                        <label>{{ __('Label (Urdu)') }}</label>
                        <input type="text" name="content[rows][__INDEX__][label_ur]" value="" dir="rtl">
                    </div>
                </div>
                <div class="row">
                    <div>
                        <label>{{ __('PDF path') }}</label>
                        <div class="cms-url-copy">
                            <input type="text" name="content[rows][__INDEX__][path]" value="" class="large-text cms-media-path-input" placeholder="assets/pdf/…">
                            <button type="button" class="button button-small cms-media-pick-btn" data-media-type="pdf">{{ __('Pick PDF') }}</button>
                        </div>
                    </div>
                </div>
                <p class="admin-paragraph-mb">
                    <button type="button" class="button button-small button-link-delete" data-pdf-row-remove>{{ __('Remove row') }}</button>
                </p>
            </div>
        </template>
    @endif
</div>
