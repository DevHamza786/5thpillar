@php
    $isEn = $locale === 'en';
    $suffix = $isEn ? '' : '_ur';
    $dir = $isEn ? '' : 'dir="rtl"';
    $galleryImages = old('content.images', $content['images'] ?? []);
    // Only treat columns as table headers when this is a table section (forms_catalog also uses "columns").
    $rawTableColumns = $currentType === 'table'
        ? old('content.columns', $content['columns'] ?? ['Column 1', 'Column 2'])
        : ['Column 1', 'Column 2'];
    $tableColumns = is_array($rawTableColumns)
        ? array_values(array_filter($rawTableColumns, static fn ($col) => is_string($col) || is_numeric($col)))
        : [];
    if ($tableColumns === []) {
        $tableColumns = ['Column 1', 'Column 2'];
    }
    $tableRows = $currentType === 'table'
        ? old('content.rows', $content['rows'] ?? [])
        : [];
    if (! is_array($galleryImages)) { $galleryImages = []; }
    if (! is_array($tableRows)) { $tableRows = []; }
@endphp

{{-- Text section --}}
<div class="cms-section-fields cms-section-fields--text" data-section-fields="text" @if($currentType !== 'text') hidden @endif>
    @if ($isEn)
        <div class="row cols-2"><div><label>{{ __('Heading') }}</label><input type="text" name="content[heading]" value="{{ old('content.heading', $content['heading'] ?? '') }}" class="large-text"></div><div><label>{{ __('Subheading') }}</label><input type="text" name="content[subheading]" value="{{ old('content.subheading', $content['subheading'] ?? '') }}" class="large-text"></div></div>
        <div class="row"><div><label>{{ __('Content') }}</label><textarea name="content[content]" rows="6" class="large-text">{{ old('content.content', $content['content'] ?? '') }}</textarea></div></div>
        <div class="row cols-2"><div><label>{{ __('Button text') }}</label><input type="text" name="content[button_text]" value="{{ old('content.button_text', $content['button_text'] ?? '') }}"></div><div><label>{{ __('Button URL') }}</label><input type="text" name="content[button_url]" value="{{ old('content.button_url', $content['button_url'] ?? '') }}" placeholder="/contact"></div></div>
    @else
        <div class="row cols-2"><div><label>{{ __('Heading (Urdu)') }}</label><input type="text" name="content[heading_ur]" value="{{ old('content.heading_ur', $content['heading_ur'] ?? '') }}" class="large-text" dir="rtl"></div><div><label>{{ __('Subheading (Urdu)') }}</label><input type="text" name="content[subheading_ur]" value="{{ old('content.subheading_ur', $content['subheading_ur'] ?? '') }}" class="large-text" dir="rtl"></div></div>
        <div class="row"><div><label>{{ __('Content (Urdu)') }}</label><textarea name="content[content_ur]" rows="6" class="large-text" dir="rtl">{{ old('content.content_ur', $content['content_ur'] ?? '') }}</textarea></div></div>
        <div class="row cols-2"><div><label>{{ __('Button text (Urdu)') }}</label><input type="text" name="content[button_text_ur]" value="{{ old('content.button_text_ur', $content['button_text_ur'] ?? '') }}" dir="rtl"></div></div>
    @endif
</div>

{{-- Image section --}}
<div class="cms-section-fields cms-section-fields--image" data-section-fields="image" @if($currentType !== 'image') hidden @endif>
    @if ($isEn)
        <div class="row"><div><label>{{ __('Image') }}</label><div class="cms-url-copy"><input type="text" name="content[image]" value="{{ old('content.image', $content['image'] ?? '') }}" class="large-text cms-media-path-input"><button type="button" class="button button-small cms-media-pick-btn" data-media-type="image">{{ __('Pick image') }}</button></div></div></div>
        <div class="row cols-2"><div><label>{{ __('Alt text') }}</label><input type="text" name="content[alt]" value="{{ old('content.alt', $content['alt'] ?? '') }}"></div><div><label>{{ __('Caption') }}</label><input type="text" name="content[caption]" value="{{ old('content.caption', $content['caption'] ?? '') }}"></div></div>
    @else
        <div class="row"><div><label>{{ __('Image (Urdu)') }}</label><div class="cms-url-copy"><input type="text" name="content[image_ur]" value="{{ old('content.image_ur', $content['image_ur'] ?? '') }}" class="large-text cms-media-path-input"><button type="button" class="button button-small cms-media-pick-btn" data-media-type="image">{{ __('Pick image') }}</button></div></div></div>
        <div class="row cols-2"><div><label>{{ __('Alt text (Urdu)') }}</label><input type="text" name="content[alt_ur]" value="{{ old('content.alt_ur', $content['alt_ur'] ?? '') }}" dir="rtl"></div><div><label>{{ __('Caption (Urdu)') }}</label><input type="text" name="content[caption_ur]" value="{{ old('content.caption_ur', $content['caption_ur'] ?? '') }}" dir="rtl"></div></div>
    @endif
</div>

{{-- Gallery section --}}
<div class="cms-section-fields cms-section-fields--gallery" data-section-fields="gallery" @if($currentType !== 'gallery') hidden @endif>
    @if ($isEn)
        <div data-cms-rows data-cms-row-template="cms-gallery-image-template">
            @foreach ($galleryImages as $index => $image)
                <div class="cms-repeat-row" data-cms-row>
                    <div class="row cols-2"><div><label>{{ __('Image') }} {{ $index + 1 }}</label><div class="cms-url-copy"><input type="text" name="content[images][{{ $index }}][path]" value="{{ $image['path'] ?? '' }}" class="large-text cms-media-path-input"><button type="button" class="button button-small cms-media-pick-btn" data-media-type="image">{{ __('Pick') }}</button></div></div><div><label>{{ __('Sort order') }}</label><input type="number" name="content[images][{{ $index }}][sort_order]" value="{{ $image['sort_order'] ?? $index }}" min="0"></div></div>
                    <div class="row"><div><label>{{ __('Alt text') }}</label><input type="text" name="content[images][{{ $index }}][alt]" value="{{ $image['alt'] ?? '' }}"></div></div>
                    <p><button type="button" class="button button-small button-link-delete" data-cms-row-remove>{{ __('Remove image') }}</button></p>
                </div>
            @endforeach
        </div>
        <p><button type="button" class="button" data-cms-row-add>{{ __('Add image') }}</button></p>
        <template id="cms-gallery-image-template">
            <div class="cms-repeat-row" data-cms-row>
                <div class="row cols-2"><div><label>{{ __('Image') }}</label><div class="cms-url-copy"><input type="text" name="content[images][__INDEX__][path]" value="" class="large-text cms-media-path-input"><button type="button" class="button button-small cms-media-pick-btn" data-media-type="image">{{ __('Pick') }}</button></div></div><div><label>{{ __('Sort order') }}</label><input type="number" name="content[images][__INDEX__][sort_order]" value="__INDEX__" min="0"></div></div>
                <div class="row"><div><label>{{ __('Alt text') }}</label><input type="text" name="content[images][__INDEX__][alt]" value=""></div></div>
                <p><button type="button" class="button button-small button-link-delete" data-cms-row-remove>{{ __('Remove image') }}</button></p>
            </div>
        </template>
    @endif
</div>

{{-- Video section --}}
<div class="cms-section-fields cms-section-fields--video" data-section-fields="video" @if($currentType !== 'video') hidden @endif>
    @if ($isEn)
        <div class="row"><div><label>{{ __('Video file') }}</label><div class="cms-url-copy"><input type="text" name="content[video_file]" value="{{ old('content.video_file', $content['video_file'] ?? '') }}" class="large-text cms-media-path-input" placeholder="assets/…"></div></div></div>
        <div class="row"><div><label>{{ __('Embed URL') }}</label><input type="text" name="content[embed_url]" value="{{ old('content.embed_url', $content['embed_url'] ?? '') }}" class="large-text" placeholder="https://www.youtube.com/embed/…"></div></div>
        <div class="row cols-2"><div><label>{{ __('Thumbnail') }}</label><div class="cms-url-copy"><input type="text" name="content[thumbnail]" value="{{ old('content.thumbnail', $content['thumbnail'] ?? '') }}" class="large-text cms-media-path-input"><button type="button" class="button button-small cms-media-pick-btn" data-media-type="image">{{ __('Pick image') }}</button></div></div><div><label>{{ __('Caption') }}</label><input type="text" name="content[caption]" value="{{ old('content.caption', $content['caption'] ?? '') }}"></div></div>
    @else
        <div class="row"><div><label>{{ __('Caption (Urdu)') }}</label><input type="text" name="content[caption_ur]" value="{{ old('content.caption_ur', $content['caption_ur'] ?? '') }}" dir="rtl"></div></div>
    @endif
</div>

{{-- PDF section --}}
<div class="cms-section-fields cms-section-fields--pdf" data-section-fields="pdf" @if($currentType !== 'pdf') hidden @endif>
    @if ($isEn)
        <div class="row"><div><label>{{ __('PDF file') }}</label><div class="cms-url-copy"><input type="text" name="content[pdf_path]" value="{{ old('content.pdf_path', $content['pdf_path'] ?? '') }}" class="large-text cms-media-path-input"><button type="button" class="button button-small cms-media-pick-btn" data-media-type="pdf">{{ __('Pick PDF') }}</button></div></div></div>
        <div class="row"><div><label>{{ __('Download label') }}</label><input type="text" name="content[download_label]" value="{{ old('content.download_label', $content['download_label'] ?? 'Download') }}"></div></div>
    @else
        <div class="row"><div><label>{{ __('PDF file (Urdu)') }}</label><div class="cms-url-copy"><input type="text" name="content[pdf_path_ur]" value="{{ old('content.pdf_path_ur', $content['pdf_path_ur'] ?? '') }}" class="large-text cms-media-path-input"><button type="button" class="button button-small cms-media-pick-btn" data-media-type="pdf">{{ __('Pick PDF') }}</button></div></div></div>
        <div class="row"><div><label>{{ __('Download label (Urdu)') }}</label><input type="text" name="content[download_label_ur]" value="{{ old('content.download_label_ur', $content['download_label_ur'] ?? '') }}" dir="rtl"></div></div>
    @endif
</div>

{{-- Table section --}}
<div class="cms-section-fields cms-section-fields--table" data-section-fields="table" @if($currentType !== 'table') hidden @endif>
    @if ($isEn)
        <div class="row"><div><label>{{ __('Table heading') }}</label><input type="text" name="content[heading]" value="{{ old('content.heading', $content['heading'] ?? '') }}" class="large-text"></div></div>
        <div class="row"><div><label>{{ __('Columns (comma-separated)') }}</label><input type="text" name="content[columns_csv]" value="{{ old('content.columns_csv', implode(', ', $tableColumns)) }}" class="large-text" data-table-columns-input></div></div>
        <input type="hidden" name="content[columns]" value="" data-table-columns-hidden>
        <div data-cms-rows data-cms-row-template="cms-table-row-template">
            @foreach ($tableRows as $index => $row)
                @php $cells = is_array($row['cells'] ?? null) ? $row['cells'] : []; @endphp
                <div class="cms-repeat-row" data-cms-row>
                    <label>{{ __('Row') }} {{ $index + 1 }}</label>
                    <input type="text" name="content[rows][{{ $index }}][cells_csv]" value="{{ implode(' | ', $cells) }}" class="large-text" placeholder="Cell 1 | Cell 2 | Cell 3" data-table-row-input>
                    <p><button type="button" class="button button-small button-link-delete" data-cms-row-remove>{{ __('Remove row') }}</button></p>
                </div>
            @endforeach
        </div>
        <p><button type="button" class="button" data-cms-row-add>{{ __('Add row') }}</button></p>
        <template id="cms-table-row-template">
            <div class="cms-repeat-row" data-cms-row>
                <label>{{ __('Row') }}</label>
                <input type="text" name="content[rows][__INDEX__][cells_csv]" value="" class="large-text" placeholder="Cell 1 | Cell 2 | Cell 3" data-table-row-input>
                <p><button type="button" class="button button-small button-link-delete" data-cms-row-remove>{{ __('Remove row') }}</button></p>
            </div>
        </template>
    @else
        <div class="row"><div><label>{{ __('Table heading (Urdu)') }}</label><input type="text" name="content[heading_ur]" value="{{ old('content.heading_ur', $content['heading_ur'] ?? '') }}" class="large-text" dir="rtl"></div></div>
    @endif
</div>

{{-- Rich content section --}}
<div class="cms-section-fields cms-section-fields--rich-content" data-section-fields="rich_content" @if($currentType !== 'rich_content') hidden @endif>
    @if ($isEn)
        <div class="row"><div><label>{{ __('Rich content') }}</label><input id="rich-content-{{ $section?->id ?? 'new' }}" type="hidden" name="content[html]" value="{{ old('content.html', $content['html'] ?? '') }}"><trix-editor input="rich-content-{{ $section?->id ?? 'new' }}" class="cms-trix-editor"></trix-editor></div></div>
    @else
        <div class="row"><div><label>{{ __('Rich content (Urdu)') }}</label><input id="rich-content-ur-{{ $section?->id ?? 'new' }}" type="hidden" name="content[html_ur]" value="{{ old('content.html_ur', $content['html_ur'] ?? '') }}"><trix-editor input="rich-content-ur-{{ $section?->id ?? 'new' }}" class="cms-trix-editor" dir="rtl"></trix-editor></div></div>
    @endif
</div>
