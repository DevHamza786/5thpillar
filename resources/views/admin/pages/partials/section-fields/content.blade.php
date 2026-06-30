@php
    $suffix = $locale === 'ur' ? '_ur' : '';
    $isEn = $locale === 'en';
@endphp
<div class="cms-section-fields cms-section-fields--content" data-section-fields="content" @if($currentType !== 'content') hidden @endif>
    @if ($isEn)
        <div class="row">
            <div>
                <label>{{ __('Heading') }}</label>
                <input type="text" name="heading" value="{{ old('heading', $section?->heading) }}">
            </div>
        </div>
        <div class="row">
            <div>
                <label>{{ __('Body HTML') }}</label>
                <textarea name="body_html" rows="6" class="cms-rich-text">{{ old('body_html', $section?->body_html) }}</textarea>
            </div>
        </div>
    @else
        <div class="row">
            <div>
                <label>{{ __('Heading (Urdu)') }}</label>
                <input type="text" name="heading_ur" value="{{ old('heading_ur', $section?->heading_ur) }}" dir="rtl">
            </div>
        </div>
        <div class="row">
            <div>
                <label>{{ __('Body HTML (Urdu)') }}</label>
                <textarea name="body_html_ur" rows="6" class="cms-rich-text" dir="rtl">{{ old('body_html_ur', $section?->body_html_ur) }}</textarea>
            </div>
        </div>
    @endif
</div>
