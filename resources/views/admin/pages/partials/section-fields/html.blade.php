@php
    $field = $locale === 'ur' ? 'html_ur' : 'html';
    $isEn = $locale === 'en';
@endphp
<div class="cms-section-fields cms-section-fields--html" data-section-fields="html" @if($currentType !== 'html') hidden @endif>
    <div class="row">
        <div>
            <label>{{ $isEn ? __('HTML content') : __('HTML content (Urdu)') }}</label>
            <textarea name="content[{{ $field }}]" rows="8" class="cms-rich-text" @if(!$isEn) dir="rtl" @endif>{{ old("content.{$field}", $content[$field] ?? '') }}</textarea>
        </div>
    </div>
</div>
