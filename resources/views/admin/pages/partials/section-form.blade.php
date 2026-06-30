@php
    /** @var \App\Models\PageSection|null $section */
    /** @var string $formAction */
    /** @var string $formMethod */
    /** @var string $submitLabel */
    /** @var array<string, array<string, mixed>> $sectionTypes */
    /** @var array<string, string> $sectionRoles */
    $section = $section ?? null;
    $prefix = $section ? '' : 'new_';
    $content = old('content', $section?->content ?? []);
    $settings = old('settings', $section?->settings ?? []);
    $currentType = old('section_type', $section?->section_type ?? 'text');
@endphp
<form
    method="post"
    action="{{ $formAction }}"
    class="cms-section-form"
    data-section-form
>
    @csrf
    @if (($formMethod ?? 'POST') !== 'POST')
        @method($formMethod)
    @endif

    <div class="cms-section-form__toolbar">
        @if ($section)
            <span class="description">{{ __('Section') }} #{{ $section->sort_order }}</span>
        @endif
    </div>

    <div class="row cols-2">
        <div>
            <label>{{ __('Section type') }}</label>
            <select name="section_type" data-section-type-select @if($section) data-section-id="{{ $section->id }}" @endif>
                @foreach ($sectionTypes as $typeKey => $typeMeta)
                    <option value="{{ $typeKey }}" @selected($currentType === $typeKey)>{{ __($typeMeta['label'] ?? $typeKey) }}</option>
                @endforeach
            </select>
            <p class="description">{{ __($sectionTypes[$currentType]['description'] ?? '') }}</p>
        </div>
        <div>
            <label>{{ __('Placement') }}</label>
            <select name="settings[role]" data-section-role-select>
                @foreach ($sectionRoles as $roleKey => $roleLabel)
                    @if (in_array($roleKey, $sectionTypes[$currentType]['roles'] ?? ['append'], true))
                        <option value="{{ $roleKey }}" @selected(($settings['role'] ?? 'append') === $roleKey)>{{ __($roleLabel) }}</option>
                    @endif
                @endforeach
            </select>
        </div>
    </div>

    <div class="row cols-2" data-home-slot-row @if(($settings['role'] ?? '') !== 'home') hidden @endif>
        <div>
            <label>{{ __('Homepage slot') }}</label>
            <select name="settings[slot]">
                @foreach (config('cms.home_slots', []) as $slotKey => $slotLabel)
                    <option value="{{ $slotKey }}" @selected(($settings['slot'] ?? '') === $slotKey)>{{ __($slotLabel) }}</option>
                @endforeach
            </select>
            <p class="description">{{ __('Which homepage area this section fills.') }}</p>
        </div>
    </div>

    <div class="row cols-2">
        <div>
            <label>{{ __('Order') }}</label>
            <input type="number" name="sort_order" value="{{ old('sort_order', $section?->sort_order ?? 0) }}" min="0">
        </div>
        <div>
            <p class="check admin-check-mt">
                <input id="enabled-{{ $section?->id ?? 'new' }}" type="checkbox" name="is_enabled" value="1" @checked(old('is_enabled', $section?->is_enabled ?? true))>
                <label for="enabled-{{ $section?->id ?? 'new' }}">{{ __('Enabled on site') }}</label>
            </p>
        </div>
    </div>

    <div class="cms-bilingual-tabs" data-bilingual-tabs>
        <div class="cms-bilingual-tabs__nav">
            <button type="button" class="is-active" data-tab-target="en">{{ __('English') }}</button>
            <button type="button" data-tab-target="ur">{{ __('Urdu') }}</button>
        </div>

        <div class="cms-bilingual-tabs__panel is-active" data-tab-panel="en">
            @include('admin.pages.partials.section-fields.structured-sections', ['locale' => 'en', 'section' => $section, 'content' => $content, 'currentType' => $currentType])
            @include('admin.pages.partials.section-fields.content', ['locale' => 'en', 'section' => $section, 'content' => $content, 'currentType' => $currentType])
            @include('admin.pages.partials.section-fields.html', ['locale' => 'en', 'content' => $content, 'currentType' => $currentType])
            @include('admin.pages.partials.section-fields.pdf-table', ['locale' => 'en', 'content' => $content, 'settings' => $settings, 'currentType' => $currentType])
            @include('admin.pages.partials.section-fields.cms-blocks', ['locale' => 'en', 'content' => $content, 'settings' => $settings, 'currentType' => $currentType])
        </div>

        <div class="cms-bilingual-tabs__panel" data-tab-panel="ur">
            @include('admin.pages.partials.section-fields.structured-sections', ['locale' => 'ur', 'section' => $section, 'content' => $content, 'currentType' => $currentType])
            @include('admin.pages.partials.section-fields.content', ['locale' => 'ur', 'section' => $section, 'content' => $content, 'currentType' => $currentType])
            @include('admin.pages.partials.section-fields.html', ['locale' => 'ur', 'content' => $content, 'currentType' => $currentType])
            @include('admin.pages.partials.section-fields.pdf-table', ['locale' => 'ur', 'content' => $content, 'settings' => $settings, 'currentType' => $currentType])
            @include('admin.pages.partials.section-fields.cms-blocks', ['locale' => 'ur', 'content' => $content, 'settings' => $settings, 'currentType' => $currentType])
        </div>
    </div>

    <p class="admin-flex-row admin-flex-row--mt">
        <button type="submit" class="button button-primary">{{ $submitLabel }}</button>
        @if ($section)
            <a href="{{ route('admin.pages.sections.duplicate', [$page, $section]) }}" class="button" onclick="event.preventDefault(); document.getElementById('dup-{{ $section->id }}').submit();">{{ __('Duplicate') }}</a>
        @endif
    </p>
</form>

@if ($section)
    <form id="dup-{{ $section->id }}" method="post" action="{{ route('admin.pages.sections.duplicate', [$page, $section]) }}" class="admin-hidden">
        @csrf
    </form>
    <form method="post" action="{{ route('admin.pages.sections.destroy', [$page, $section]) }}" class="admin-section-delete" onsubmit='return confirm({{ json_encode(__('Remove this section?')) }});'>
        @csrf
        @method('DELETE')
        <button type="submit" class="button button-link-delete">{{ __('Delete section') }}</button>
    </form>
@endif
