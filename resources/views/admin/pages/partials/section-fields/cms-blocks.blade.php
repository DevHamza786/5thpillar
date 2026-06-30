@php
    $isEn = $locale === 'en';
    $slides = old('content.slides', $content['slides'] ?? []);
    $cards = old('content.cards', $content['cards'] ?? []);
    $items = old('content.items', $content['items'] ?? []);
    $blocks = old('content.blocks', $content['blocks'] ?? []);
    $members = old('content.members', $content['members'] ?? []);
    if (! is_array($slides)) { $slides = []; }
    if (! is_array($cards)) { $cards = []; }
    if (! is_array($items)) { $items = []; }
    if (! is_array($blocks)) { $blocks = []; }
    if (! is_array($members)) { $members = []; }
@endphp

{{-- Home popup --}}
<div class="cms-section-fields cms-section-fields--home-popup" data-section-fields="home_popup" @if($currentType !== 'home_popup') hidden @endif>
    @if ($isEn)
        <p class="check"><input type="hidden" name="content[enabled]" value="0"><input type="checkbox" name="content[enabled]" value="1" @checked(old('content.enabled', $content['enabled'] ?? true))><label>{{ __('Show popup on homepage') }}</label></p>
        <div class="row"><div><label>{{ __('Popup image') }}</label><div class="cms-url-copy"><input type="text" name="content[image]" value="{{ old('content.image', $content['image'] ?? '') }}" class="large-text cms-media-path-input"><button type="button" class="button button-small cms-media-pick-btn" data-media-type="image">{{ __('Pick image') }}</button></div></div></div>
        <div class="row"><div><label>{{ __('Aria label') }}</label><input type="text" name="content[aria_label]" value="{{ old('content.aria_label', $content['aria_label'] ?? '') }}" class="large-text"></div></div>
    @else
        <div class="row"><div><label>{{ __('Image alt (Urdu)') }}</label><input type="text" name="content[alt_ur]" value="{{ old('content.alt_ur', $content['alt_ur'] ?? '') }}" dir="rtl"></div></div>
        <div class="row"><div><label>{{ __('Aria label (Urdu)') }}</label><input type="text" name="content[aria_label_ur]" value="{{ old('content.aria_label_ur', $content['aria_label_ur'] ?? '') }}" dir="rtl"></div></div>
    @endif
</div>

{{-- Hero slider --}}
<div class="cms-section-fields cms-section-fields--hero-slider" data-section-fields="hero_slider" @if($currentType !== 'hero_slider') hidden @endif>
    @if ($isEn)
        <div data-cms-rows data-cms-row-template="cms-hero-slide-template">
            @foreach ($slides as $index => $slide)
                <div class="cms-repeat-row" data-cms-row>
                    <h4 class="admin-heading-sm">{{ __('Slide') }} {{ $index + 1 }}</h4>
                    <div class="row cols-2">
                        <div><label>{{ __('Title') }}</label><input type="text" name="content[slides][{{ $index }}][title]" value="{{ $slide['title'] ?? '' }}"></div>
                        <div><label>{{ __('Title line 2') }}</label><input type="text" name="content[slides][{{ $index }}][title_line2]" value="{{ $slide['title_line2'] ?? '' }}"></div>
                    </div>
                    <div class="row cols-2">
                        <div><label>{{ __('Title (Urdu)') }}</label><input type="text" name="content[slides][{{ $index }}][title_ur]" value="{{ $slide['title_ur'] ?? '' }}" dir="rtl"></div>
                        <div><label>{{ __('Title line 2 (Urdu)') }}</label><input type="text" name="content[slides][{{ $index }}][title_line2_ur]" value="{{ $slide['title_line2_ur'] ?? '' }}" dir="rtl"></div>
                    </div>
                    <div class="row cols-2">
                        <div><label>{{ __('CTA text') }}</label><input type="text" name="content[slides][{{ $index }}][cta_text]" value="{{ $slide['cta_text'] ?? '' }}"></div>
                        <div><label>{{ __('CTA link') }}</label><input type="text" name="content[slides][{{ $index }}][cta_link]" value="{{ $slide['cta_link'] ?? '' }}" placeholder="/hajj-planner"></div>
                    </div>
                    <div class="row"><div><label>{{ __('Background image') }}</label><div class="cms-url-copy"><input type="text" name="content[slides][{{ $index }}][bg]" value="{{ $slide['bg'] ?? '' }}" class="large-text cms-media-path-input"><button type="button" class="button button-small cms-media-pick-btn" data-media-type="image">{{ __('Pick image') }}</button></div></div></div>
                    <p><button type="button" class="button button-small button-link-delete" data-cms-row-remove>{{ __('Remove slide') }}</button></p>
                </div>
            @endforeach
        </div>
        <p><button type="button" class="button" data-cms-row-add data-cms-rows-wrap>{{ __('Add slide') }}</button></p>
        <template id="cms-hero-slide-template">
            <div class="cms-repeat-row" data-cms-row>
                <h4 class="admin-heading-sm">{{ __('Slide') }}</h4>
                <div class="row cols-2"><div><label>{{ __('Title') }}</label><input type="text" name="content[slides][__INDEX__][title]" value=""></div><div><label>{{ __('Title line 2') }}</label><input type="text" name="content[slides][__INDEX__][title_line2]" value=""></div></div>
                <div class="row cols-2"><div><label>{{ __('Title (Urdu)') }}</label><input type="text" name="content[slides][__INDEX__][title_ur]" value="" dir="rtl"></div><div><label>{{ __('Title line 2 (Urdu)') }}</label><input type="text" name="content[slides][__INDEX__][title_line2_ur]" value="" dir="rtl"></div></div>
                <div class="row cols-2"><div><label>{{ __('CTA text') }}</label><input type="text" name="content[slides][__INDEX__][cta_text]" value=""></div><div><label>{{ __('CTA link') }}</label><input type="text" name="content[slides][__INDEX__][cta_link]" value=""></div></div>
                <div class="row"><div><label>{{ __('Background image') }}</label><div class="cms-url-copy"><input type="text" name="content[slides][__INDEX__][bg]" value="" class="large-text cms-media-path-input"><button type="button" class="button button-small cms-media-pick-btn" data-media-type="image">{{ __('Pick image') }}</button></div></div></div>
                <p><button type="button" class="button button-small button-link-delete" data-cms-row-remove>{{ __('Remove slide') }}</button></p>
            </div>
        </template>
    @endif
</div>

{{-- Home about banner --}}
<div class="cms-section-fields cms-section-fields--home-about" data-section-fields="home_about_banner" @if($currentType !== 'home_about_banner') hidden @endif>
    @if ($isEn)
        <div class="row cols-2"><div><label>{{ __('Kicker') }}</label><input type="text" name="content[kicker]" value="{{ old('content.kicker', $content['kicker'] ?? 'ABOUT') }}"></div><div><label>{{ __('Background image') }}</label><div class="cms-url-copy"><input type="text" name="content[bg_image]" value="{{ old('content.bg_image', $content['bg_image'] ?? '') }}" class="large-text cms-media-path-input"><button type="button" class="button button-small cms-media-pick-btn" data-media-type="image">{{ __('Pick image') }}</button></div></div></div>
        <div class="row cols-2"><div><label>{{ __('Title') }}</label><input type="text" name="content[title]" value="{{ old('content.title', $content['title'] ?? '') }}"></div><div><label>{{ __('Title line 2') }}</label><input type="text" name="content[title_line2]" value="{{ old('content.title_line2', $content['title_line2'] ?? '') }}"></div></div>
        <div class="row"><div><label>{{ __('Body text') }}</label><textarea name="content[text]" rows="4" class="large-text">{{ old('content.text', $content['text'] ?? '') }}</textarea></div></div>
        <div class="row cols-2"><div><label>{{ __('Button text') }}</label><input type="text" name="content[cta_text]" value="{{ old('content.cta_text', $content['cta_text'] ?? '') }}"></div><div><label>{{ __('Button link') }}</label><input type="text" name="content[cta_link]" value="{{ old('content.cta_link', $content['cta_link'] ?? '/about-us') }}"></div></div>
    @else
        <div class="row cols-2"><div><label>{{ __('Kicker (Urdu)') }}</label><input type="text" name="content[kicker_ur]" value="{{ old('content.kicker_ur', $content['kicker_ur'] ?? '') }}" dir="rtl"></div><div><label>{{ __('Title (Urdu)') }}</label><input type="text" name="content[title_ur]" value="{{ old('content.title_ur', $content['title_ur'] ?? '') }}" dir="rtl"></div></div>
        <div class="row cols-2"><div><label>{{ __('Title line 2 (Urdu)') }}</label><input type="text" name="content[title_line2_ur]" value="{{ old('content.title_line2_ur', $content['title_line2_ur'] ?? '') }}" dir="rtl"></div><div><label>{{ __('Button text (Urdu)') }}</label><input type="text" name="content[cta_text_ur]" value="{{ old('content.cta_text_ur', $content['cta_text_ur'] ?? '') }}" dir="rtl"></div></div>
        <div class="row"><div><label>{{ __('Body text (Urdu)') }}</label><textarea name="content[text_ur]" rows="4" class="large-text" dir="rtl">{{ old('content.text_ur', $content['text_ur'] ?? '') }}</textarea></div></div>
    @endif
</div>

{{-- Icon cards --}}
<div class="cms-section-fields cms-section-fields--icon-cards" data-section-fields="icon_cards" @if($currentType !== 'icon_cards') hidden @endif>
    @if ($isEn)
        <div class="row"><div><label>{{ __('Section heading') }}</label><input type="text" name="content[heading]" value="{{ old('content.heading', $content['heading'] ?? '') }}" class="large-text"></div></div>
        <div data-cms-rows data-cms-row-template="cms-icon-card-template">
            @foreach ($cards as $index => $card)
                <div class="cms-repeat-row" data-cms-row>
                    <div class="row cols-2"><div><label>{{ __('Card title') }}</label><input type="text" name="content[cards][{{ $index }}][title]" value="{{ $card['title'] ?? '' }}"></div><div><label>{{ __('Card title (Urdu)') }}</label><input type="text" name="content[cards][{{ $index }}][title_ur]" value="{{ $card['title_ur'] ?? '' }}" dir="rtl"></div></div>
                    <div class="row"><div><label>{{ __('Card text') }}</label><textarea name="content[cards][{{ $index }}][text]" rows="3">{{ $card['text'] ?? '' }}</textarea></div></div>
                    <div class="row"><div><label>{{ __('Icon image') }}</label><div class="cms-url-copy"><input type="text" name="content[cards][{{ $index }}][icon]" value="{{ $card['icon'] ?? '' }}" class="large-text cms-media-path-input"><button type="button" class="button button-small cms-media-pick-btn" data-media-type="image">{{ __('Pick image') }}</button></div></div></div>
                    <p><button type="button" class="button button-small button-link-delete" data-cms-row-remove>{{ __('Remove card') }}</button></p>
                </div>
            @endforeach
        </div>
        <p><button type="button" class="button" data-cms-row-add>{{ __('Add card') }}</button></p>
        <template id="cms-icon-card-template">
            <div class="cms-repeat-row" data-cms-row>
                <div class="row cols-2"><div><label>{{ __('Card title') }}</label><input type="text" name="content[cards][__INDEX__][title]" value=""></div><div><label>{{ __('Card title (Urdu)') }}</label><input type="text" name="content[cards][__INDEX__][title_ur]" value="" dir="rtl"></div></div>
                <div class="row"><div><label>{{ __('Card text') }}</label><textarea name="content[cards][__INDEX__][text]" rows="3"></textarea></div></div>
                <div class="row"><div><label>{{ __('Icon image') }}</label><div class="cms-url-copy"><input type="text" name="content[cards][__INDEX__][icon]" value="" class="large-text cms-media-path-input"><button type="button" class="button button-small cms-media-pick-btn" data-media-type="image">{{ __('Pick image') }}</button></div></div></div>
                <p><button type="button" class="button button-small button-link-delete" data-cms-row-remove>{{ __('Remove card') }}</button></p>
            </div>
        </template>
    @else
        <div class="row"><div><label>{{ __('Section heading (Urdu)') }}</label><input type="text" name="content[heading_ur]" value="{{ old('content.heading_ur', $content['heading_ur'] ?? '') }}" dir="rtl" class="large-text"></div></div>
    @endif
</div>

{{-- Value chain --}}
<div class="cms-section-fields cms-section-fields--value-chain" data-section-fields="value_chain" @if($currentType !== 'value_chain') hidden @endif>
    @if ($isEn)
        <div class="row"><div><label>{{ __('Title') }}</label><input type="text" name="content[title]" value="{{ old('content.title', $content['title'] ?? '') }}" class="large-text"></div></div>
        <div class="row cols-2"><div><label>{{ __('Animation image (EN)') }}</label><div class="cms-url-copy"><input type="text" name="content[image]" value="{{ old('content.image', $content['image'] ?? '') }}" class="large-text cms-media-path-input"><button type="button" class="button button-small cms-media-pick-btn" data-media-type="image">{{ __('Pick image') }}</button></div></div><div><label>{{ __('PDF download') }}</label><div class="cms-url-copy"><input type="text" name="content[pdf_path]" value="{{ old('content.pdf_path', $content['pdf_path'] ?? '') }}" class="large-text cms-media-path-input"><button type="button" class="button button-small cms-media-pick-btn" data-media-type="pdf">{{ __('Pick PDF') }}</button></div></div></div>
        <div class="row"><div><label>{{ __('Button label') }}</label><input type="text" name="content[button_label]" value="{{ old('content.button_label', $content['button_label'] ?? '') }}"></div></div>
    @else
        <div class="row"><div><label>{{ __('Title (Urdu)') }}</label><input type="text" name="content[title_ur]" value="{{ old('content.title_ur', $content['title_ur'] ?? '') }}" dir="rtl" class="large-text"></div></div>
        <div class="row cols-2"><div><label>{{ __('Animation image (Urdu)') }}</label><div class="cms-url-copy"><input type="text" name="content[image_ur]" value="{{ old('content.image_ur', $content['image_ur'] ?? '') }}" class="large-text cms-media-path-input"><button type="button" class="button button-small cms-media-pick-btn" data-media-type="image">{{ __('Pick image') }}</button></div></div><div><label>{{ __('PDF download (Urdu)') }}</label><div class="cms-url-copy"><input type="text" name="content[pdf_path_ur]" value="{{ old('content.pdf_path_ur', $content['pdf_path_ur'] ?? '') }}" class="large-text cms-media-path-input"><button type="button" class="button button-small cms-media-pick-btn" data-media-type="pdf">{{ __('Pick PDF') }}</button></div></div></div>
        <div class="row"><div><label>{{ __('Button label (Urdu)') }}</label><input type="text" name="content[button_label_ur]" value="{{ old('content.button_label_ur', $content['button_label_ur'] ?? '') }}" dir="rtl"></div></div>
    @endif
</div>

{{-- Intro milestones --}}
<div class="cms-section-fields cms-section-fields--intro-milestones" data-section-fields="intro_milestones" @if($currentType !== 'intro_milestones') hidden @endif>
    @if ($isEn)
        <div class="row"><div><label>{{ __('Lead paragraph') }}</label><textarea name="content[lead]" rows="4" class="large-text">{{ old('content.lead', $content['lead'] ?? '') }}</textarea></div></div>
        <div data-cms-rows data-cms-row-template="cms-milestone-template">
            @foreach ($items as $index => $item)
                <div class="cms-repeat-row" data-cms-row>
                    <div class="row cols-2"><div><label>{{ __('Milestone') }}</label><input type="text" name="content[items][{{ $index }}][text]" value="{{ $item['text'] ?? '' }}" class="large-text"></div><div><label>{{ __('Milestone (Urdu)') }}</label><input type="text" name="content[items][{{ $index }}][text_ur]" value="{{ $item['text_ur'] ?? '' }}" dir="rtl" class="large-text"></div></div>
                    <p><button type="button" class="button button-small button-link-delete" data-cms-row-remove>{{ __('Remove') }}</button></p>
                </div>
            @endforeach
        </div>
        <p><button type="button" class="button" data-cms-row-add>{{ __('Add milestone') }}</button></p>
        <template id="cms-milestone-template">
            <div class="cms-repeat-row" data-cms-row>
                <div class="row cols-2"><div><label>{{ __('Milestone') }}</label><input type="text" name="content[items][__INDEX__][text]" value="" class="large-text"></div><div><label>{{ __('Milestone (Urdu)') }}</label><input type="text" name="content[items][__INDEX__][text_ur]" value="" dir="rtl" class="large-text"></div></div>
                <p><button type="button" class="button button-small button-link-delete" data-cms-row-remove>{{ __('Remove') }}</button></p>
            </div>
        </template>
    @else
        <div class="row"><div><label>{{ __('Lead paragraph (Urdu)') }}</label><textarea name="content[lead_ur]" rows="4" class="large-text" dir="rtl">{{ old('content.lead_ur', $content['lead_ur'] ?? '') }}</textarea></div></div>
    @endif
</div>

{{-- Sponsor band --}}
<div class="cms-section-fields cms-section-fields--sponsor-band" data-section-fields="sponsor_band" @if($currentType !== 'sponsor_band') hidden @endif>
    @if ($isEn)
        <div class="row cols-2"><div><label>{{ __('Heading') }}</label><input type="text" name="content[heading]" value="{{ old('content.heading', $content['heading'] ?? '') }}"></div><div><label>{{ __('Background image') }}</label><div class="cms-url-copy"><input type="text" name="content[bg_image]" value="{{ old('content.bg_image', $content['bg_image'] ?? '') }}" class="large-text cms-media-path-input"><button type="button" class="button button-small cms-media-pick-btn" data-media-type="image">{{ __('Pick image') }}</button></div></div></div>
        <div class="row"><div><label>{{ __('Intro') }}</label><textarea name="content[intro]" rows="3" class="large-text">{{ old('content.intro', $content['intro'] ?? '') }}</textarea></div></div>
        <div data-cms-rows data-cms-row-template="cms-sponsor-block-template">
            @foreach ($blocks as $index => $block)
                <div class="cms-repeat-row" data-cms-row>
                    <div class="row cols-2"><div><label>{{ __('Bold label') }}</label><input type="text" name="content[blocks][{{ $index }}][strong]" value="{{ $block['strong'] ?? '' }}"></div><div><label>{{ __('Bold label (Urdu)') }}</label><input type="text" name="content[blocks][{{ $index }}][strong_ur]" value="{{ $block['strong_ur'] ?? '' }}" dir="rtl"></div></div>
                    <div class="row"><div><label>{{ __('Paragraph') }}</label><textarea name="content[blocks][{{ $index }}][text]" rows="3">{{ $block['text'] ?? '' }}</textarea></div></div>
                    <p><button type="button" class="button button-small button-link-delete" data-cms-row-remove>{{ __('Remove block') }}</button></p>
                </div>
            @endforeach
        </div>
        <p><button type="button" class="button" data-cms-row-add>{{ __('Add sponsor block') }}</button></p>
        <div class="row"><div><label>{{ __('Closing paragraph') }}</label><textarea name="content[closing]" rows="3" class="large-text">{{ old('content.closing', $content['closing'] ?? '') }}</textarea></div></div>
        <template id="cms-sponsor-block-template">
            <div class="cms-repeat-row" data-cms-row>
                <div class="row cols-2"><div><label>{{ __('Bold label') }}</label><input type="text" name="content[blocks][__INDEX__][strong]" value=""></div><div><label>{{ __('Bold label (Urdu)') }}</label><input type="text" name="content[blocks][__INDEX__][strong_ur]" value="" dir="rtl"></div></div>
                <div class="row"><div><label>{{ __('Paragraph') }}</label><textarea name="content[blocks][__INDEX__][text]" rows="3"></textarea></div></div>
                <p><button type="button" class="button button-small button-link-delete" data-cms-row-remove>{{ __('Remove block') }}</button></p>
            </div>
        </template>
    @else
        <div class="row cols-2"><div><label>{{ __('Heading (Urdu)') }}</label><input type="text" name="content[heading_ur]" value="{{ old('content.heading_ur', $content['heading_ur'] ?? '') }}" dir="rtl"></div><div><label>{{ __('Background image (Urdu)') }}</label><div class="cms-url-copy"><input type="text" name="content[bg_image_ur]" value="{{ old('content.bg_image_ur', $content['bg_image_ur'] ?? '') }}" class="large-text cms-media-path-input"><button type="button" class="button button-small cms-media-pick-btn" data-media-type="image">{{ __('Pick image') }}</button></div></div></div>
        <div class="row"><div><label>{{ __('Intro (Urdu)') }}</label><textarea name="content[intro_ur]" rows="3" class="large-text" dir="rtl">{{ old('content.intro_ur', $content['intro_ur'] ?? '') }}</textarea></div></div>
        <div class="row"><div><label>{{ __('Closing (Urdu)') }}</label><textarea name="content[closing_ur]" rows="3" class="large-text" dir="rtl">{{ old('content.closing_ur', $content['closing_ur'] ?? '') }}</textarea></div></div>
    @endif
</div>

{{-- Image band --}}
<div class="cms-section-fields cms-section-fields--image-band" data-section-fields="image_band" @if($currentType !== 'image_band') hidden @endif>
    @if ($isEn)
        <div class="row"><div><label>{{ __('Heading (HTML allowed)') }}</label><input type="text" name="content[heading]" value="{{ old('content.heading', $content['heading'] ?? '') }}" class="large-text"></div></div>
        <p class="check"><input type="hidden" name="content[heading_html]" value="0"><input type="checkbox" name="content[heading_html]" value="1" @checked(old('content.heading_html', $content['heading_html'] ?? false))><label>{{ __('Render heading as HTML') }}</label></p>
        <div class="row cols-2"><div><label>{{ __('Image (EN)') }}</label><div class="cms-url-copy"><input type="text" name="content[image]" value="{{ old('content.image', $content['image'] ?? '') }}" class="large-text cms-media-path-input"><button type="button" class="button button-small cms-media-pick-btn" data-media-type="image">{{ __('Pick image') }}</button></div></div><div><label>{{ __('Background image') }}</label><div class="cms-url-copy"><input type="text" name="content[bg_image]" value="{{ old('content.bg_image', $content['bg_image'] ?? '') }}" class="large-text cms-media-path-input"><button type="button" class="button button-small cms-media-pick-btn" data-media-type="image">{{ __('Pick image') }}</button></div></div></div>
    @else
        <div class="row"><div><label>{{ __('Heading (Urdu)') }}</label><input type="text" name="content[heading_ur]" value="{{ old('content.heading_ur', $content['heading_ur'] ?? '') }}" dir="rtl" class="large-text"></div></div>
        <div class="row cols-2"><div><label>{{ __('Image (Urdu)') }}</label><div class="cms-url-copy"><input type="text" name="content[image_ur]" value="{{ old('content.image_ur', $content['image_ur'] ?? '') }}" class="large-text cms-media-path-input"><button type="button" class="button button-small cms-media-pick-btn" data-media-type="image">{{ __('Pick image') }}</button></div></div><div><label>{{ __('Background image (Urdu)') }}</label><div class="cms-url-copy"><input type="text" name="content[bg_image_ur]" value="{{ old('content.bg_image_ur', $content['bg_image_ur'] ?? '') }}" class="large-text cms-media-path-input"><button type="button" class="button button-small cms-media-pick-btn" data-media-type="image">{{ __('Pick image') }}</button></div></div></div>
    @endif
</div>

{{-- Text band --}}
<div class="cms-section-fields cms-section-fields--text-band" data-section-fields="text_band" @if($currentType !== 'text_band') hidden @endif>
    @if ($isEn)
        <div class="row cols-2"><div><label>{{ __('Heading') }}</label><input type="text" name="content[heading]" value="{{ old('content.heading', $content['heading'] ?? '') }}"></div><div><label>{{ __('Layout') }}</label><select name="content[layout]"><option value="default" @selected(($content['layout'] ?? 'default') === 'default')>{{ __('Default') }}</option><option value="retakaful" @selected(($content['layout'] ?? '') === 'retakaful')>{{ __('ReTakaful split') }}</option></select></div></div>
        <div class="row"><div><label>{{ __('Body text') }}</label><textarea name="content[text]" rows="4" class="large-text">{{ old('content.text', $content['text'] ?? '') }}</textarea></div></div>
    @else
        <div class="row"><div><label>{{ __('Heading (Urdu)') }}</label><input type="text" name="content[heading_ur]" value="{{ old('content.heading_ur', $content['heading_ur'] ?? '') }}" dir="rtl"></div></div>
        <div class="row"><div><label>{{ __('Body text (Urdu)') }}</label><textarea name="content[text_ur]" rows="4" class="large-text" dir="rtl">{{ old('content.text_ur', $content['text_ur'] ?? '') }}</textarea></div></div>
    @endif
</div>

{{-- Team grid --}}
<div class="cms-section-fields cms-section-fields--team-grid" data-section-fields="team_grid" @if($currentType !== 'team_grid') hidden @endif>
    @if ($isEn)
        <div data-cms-rows data-cms-row-template="cms-team-member-template">
            @foreach ($members as $index => $member)
                <div class="cms-repeat-row" data-cms-row>
                    <div class="row cols-2"><div><label>{{ __('Name') }}</label><input type="text" name="content[members][{{ $index }}][name]" value="{{ $member['name'] ?? '' }}"></div><div><label>{{ __('Role') }}</label><input type="text" name="content[members][{{ $index }}][subtitle]" value="{{ $member['subtitle'] ?? '' }}"></div></div>
                    <div class="row cols-2"><div><label>{{ __('Role (Urdu)') }}</label><input type="text" name="content[members][{{ $index }}][subtitle_ur]" value="{{ $member['subtitle_ur'] ?? '' }}" dir="rtl"></div><div><label>{{ __('LinkedIn URL') }}</label><input type="url" name="content[members][{{ $index }}][linkedin]" value="{{ $member['linkedin'] ?? '' }}" class="large-text"></div></div>
                    <div class="row"><div><label>{{ __('Photo') }}</label><div class="cms-url-copy"><input type="text" name="content[members][{{ $index }}][image]" value="{{ $member['image'] ?? '' }}" class="large-text cms-media-path-input"><button type="button" class="button button-small cms-media-pick-btn" data-media-type="image">{{ __('Pick image') }}</button></div></div></div>
                    <p><button type="button" class="button button-small button-link-delete" data-cms-row-remove>{{ __('Remove member') }}</button></p>
                </div>
            @endforeach
        </div>
        <p><button type="button" class="button" data-cms-row-add>{{ __('Add team member') }}</button></p>
        <template id="cms-team-member-template">
            <div class="cms-repeat-row" data-cms-row>
                <div class="row cols-2"><div><label>{{ __('Name') }}</label><input type="text" name="content[members][__INDEX__][name]" value=""></div><div><label>{{ __('Role') }}</label><input type="text" name="content[members][__INDEX__][subtitle]" value=""></div></div>
                <div class="row cols-2"><div><label>{{ __('Role (Urdu)') }}</label><input type="text" name="content[members][__INDEX__][subtitle_ur]" value="" dir="rtl"></div><div><label>{{ __('LinkedIn URL') }}</label><input type="url" name="content[members][__INDEX__][linkedin]" value="" class="large-text"></div></div>
                <div class="row"><div><label>{{ __('Photo') }}</label><div class="cms-url-copy"><input type="text" name="content[members][__INDEX__][image]" value="" class="large-text cms-media-path-input"><button type="button" class="button button-small cms-media-pick-btn" data-media-type="image">{{ __('Pick image') }}</button></div></div></div>
                <p><button type="button" class="button button-small button-link-delete" data-cms-row-remove>{{ __('Remove member') }}</button></p>
            </div>
        </template>
    @endif
</div>
