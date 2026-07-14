@extends('admin.layouts.app')

@section('title', __('Edit page'))

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/trix@2.1.13/dist/trix.min.css">
@endpush

@section('content')
    <h1 class="wp-heading-inline">{{ __('Edit page') }}</h1>
    <a href="{{ route('admin.pages.preview', $page) }}" class="page-title-action" target="_blank" rel="noopener">{{ __('Preview EN') }}</a>
    <a href="{{ route('admin.pages.preview', ['page' => $page, 'locale' => 'ur']) }}" class="page-title-action" target="_blank" rel="noopener">{{ __('Preview UR') }}</a>
    @if ($page->isPublished())
        <a href="{{ route('pages.show', ['slug' => $page->slug]) }}" class="page-title-action" target="_blank" rel="noopener">{{ __('View EN') }}</a>
    @endif
    @if ($page->isPublishedUr())
        <a href="{{ route('urdu.pages.show', ['slug' => $page->slug_ur ?: $page->slug]) }}" class="page-title-action" target="_blank" rel="noopener">{{ __('View UR') }}</a>
    @endif
    <hr class="wp-header-end">

    <p class="subtitle">
        {{ __('English:') }} <strong><a href="{{ $page->isPublished() ? route('pages.show', ['slug' => $page->slug]) : route('admin.pages.preview', $page) }}" target="_blank" rel="noopener">{{ url('/'.$page->slug) }}</a></strong>
        · <span class="cms-status cms-status--{{ $page->isPublished() ? 'published' : 'draft' }}">{{ __($statuses[$page->status] ?? $page->status) }}</span>
        @if (config('site.locale.enabled', true))
            · {{ __('Urdu:') }} <strong><code dir="rtl">{{ url('/'.config('site.locale.prefix', 'urdu').'/'.($page->slug_ur ?: $page->slug)) }}</code></strong>
            · <span class="cms-status cms-status--{{ $page->isPublishedUr() ? 'published' : 'draft' }}">{{ __($statuses[$page->status_ur] ?? $page->status_ur) }}</span>
        @endif
        · {{ __('Template:') }} <code>{{ $page->view_key ?: $page->slug }}</code>
    </p>

    <div class="cms-edit-layout" data-cms-page-editor>
        <div class="cms-edit-main">
            <div class="postbox">
                <h2 class="postbox-header">{{ __('Page settings') }}</h2>
                <div class="inside">
                    <form method="post" action="{{ route('admin.pages.update', $page) }}">
                        @csrf
                        @method('PUT')

                        <div class="cms-bilingual-tabs" data-bilingual-tabs>
                            <div class="cms-bilingual-tabs__nav">
                                <button type="button" class="is-active" data-tab-target="en">{{ __('English') }}</button>
                                <button type="button" data-tab-target="ur">{{ __('Urdu') }}</button>
                            </div>

                            <div class="cms-bilingual-tabs__panel is-active" data-tab-panel="en">
                                <div class="row cols-2">
                                    <div>
                                        <label for="title">{{ __('Title') }}</label>
                                        <input id="title" type="text" name="title" value="{{ old('title', $page->title) }}" required class="large-text">
                                    </div>
                                    <div>
                                        <label for="hero_title">{{ __('Hero / H1') }}</label>
                                        <input id="hero_title" type="text" name="hero_title" value="{{ old('hero_title', $page->hero_title) }}" class="large-text">
                                    </div>
                                </div>
                                <div class="row cols-2">
                                    <div>
                                        <label for="meta_title">{{ __('Meta title') }}</label>
                                        <input id="meta_title" type="text" name="meta_title" value="{{ old('meta_title', $page->meta_title) }}" class="large-text">
                                    </div>
                                    <div>
                                        <label for="masthead_bg">{{ __('Masthead background (CSS)') }}</label>
                                        <input id="masthead_bg" type="text" name="masthead_bg" value="{{ old('masthead_bg', $page->masthead_bg) }}" class="large-text">
                                    </div>
                                </div>
                                <div class="row">
                                    <div>
                                        <label for="meta_description">{{ __('Meta description') }}</label>
                                        <textarea id="meta_description" name="meta_description" rows="3">{{ old('meta_description', $page->meta_description) }}</textarea>
                                    </div>
                                </div>
                                <div class="row cols-2">
                                    <div>
                                        <label for="status">{{ __('English status') }}</label>
                                        <select id="status" name="status" required>
                                            @foreach ($statuses as $value => $label)
                                                <option value="{{ $value }}" @selected(old('status', $page->status) === $value)>{{ __($label) }}</option>
                                            @endforeach
                                        </select>
                                        <p class="description">{{ __('Controls visibility on the English website.') }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="cms-bilingual-tabs__panel" data-tab-panel="ur">
                                <div class="row cols-2">
                                    <div>
                                        <label for="title_ur">{{ __('Title (Urdu)') }}</label>
                                        <input id="title_ur" type="text" name="title_ur" value="{{ old('title_ur', $page->title_ur) }}" class="large-text" dir="rtl">
                                    </div>
                                    <div>
                                        <label for="hero_title_ur">{{ __('Hero / H1 (Urdu)') }}</label>
                                        <input id="hero_title_ur" type="text" name="hero_title_ur" value="{{ old('hero_title_ur', $page->hero_title_ur) }}" class="large-text" dir="rtl">
                                    </div>
                                </div>
                                <div class="row cols-2">
                                    <div>
                                        <label for="slug_ur">{{ __('URL slug (Urdu)') }}</label>
                                        <input id="slug_ur" type="text" name="slug_ur" value="{{ old('slug_ur', $page->slug_ur) }}" class="large-text" dir="rtl" placeholder="{{ __('Optional — uses English slug if empty') }}">
                                        <p class="description">{{ __('Example:') }} <code dir="rtl">{{ config('site.locale.prefix', 'urdu') }}/…</code></p>
                                    </div>
                                    <div></div>
                                </div>
                                <div class="row cols-2">
                                    <div>
                                        <label for="meta_title_ur">{{ __('Meta title (Urdu)') }}</label>
                                        <input id="meta_title_ur" type="text" name="meta_title_ur" value="{{ old('meta_title_ur', $page->meta_title_ur) }}" class="large-text" dir="rtl">
                                    </div>
                                    <div>
                                        <label for="masthead_bg_ur">{{ __('Masthead background (Urdu CSS)') }}</label>
                                        <input id="masthead_bg_ur" type="text" name="masthead_bg_ur" value="{{ old('masthead_bg_ur', $page->masthead_bg_ur) }}" class="large-text" dir="rtl">
                                    </div>
                                </div>
                                <div class="row">
                                    <div>
                                        <label for="meta_description_ur">{{ __('Meta description (Urdu)') }}</label>
                                        <textarea id="meta_description_ur" name="meta_description_ur" rows="3" dir="rtl">{{ old('meta_description_ur', $page->meta_description_ur) }}</textarea>
                                    </div>
                                </div>
                                <div class="row cols-2">
                                    <div>
                                        <label for="status_ur">{{ __('Urdu status') }}</label>
                                        <select id="status_ur" name="status_ur" required>
                                            @foreach ($statuses as $value => $label)
                                                <option value="{{ $value }}" @selected(old('status_ur', $page->status_ur ?? 'draft') === $value)>{{ __($label) }}</option>
                                            @endforeach
                                        </select>
                                        <p class="description">{{ __('Controls visibility on the Urdu website.') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row cols-2 admin-row-mt">
                            <div>
                                <label for="slug">{{ __('URL slug') }}</label>
                                <input id="slug" type="text" name="slug" value="{{ old('slug', $page->slug) }}" required pattern="[a-z0-9]+(-[a-z0-9]+)*" class="large-text">
                            </div>
                            <div>
                                <label for="view_key">{{ __('Template key') }}</label>
                                <input id="view_key" type="text" name="view_key" value="{{ old('view_key', $page->view_key) }}" pattern="[a-z0-9]+(-[a-z0-9]+)*" placeholder="{{ __('Blank = same as slug') }}" class="large-text">
                            </div>
                        </div>
                        <div class="row cols-2">
                            <div>
                                <label for="sort_order">{{ __('Sort order') }}</label>
                                <input id="sort_order" type="number" name="sort_order" value="{{ old('sort_order', $page->sort_order) }}" min="0" class="large-text">
                            </div>
                            <div>
                                <label for="meta_keywords">{{ __('Meta keywords') }}</label>
                                <input id="meta_keywords" type="text" name="meta_keywords" value="{{ old('meta_keywords', $page->meta_keywords) }}" class="large-text">
                            </div>
                        </div>
                        <div class="row cols-2">
                            <div>
                                <label for="og_image">{{ __('Open Graph image') }}</label>
                                <div class="cms-url-copy">
                                    <input id="og_image" type="text" name="og_image" value="{{ old('og_image', $page->og_image) }}" placeholder="assets/images/…" class="large-text cms-media-path-input">
                                    <button type="button" class="button button-small cms-media-pick-btn" data-media-type="image">{{ __('Pick image') }}</button>
                                </div>
                            </div>
                        </div>

                        <p class="submit admin-submit-row admin-submit-row--12">
                            <button type="submit" class="button button-primary">{{ __('Update page') }}</button>
                        </p>
                    </form>
                </div>
            </div>

            <div class="postbox cms-sections-postbox">
                <h2 class="postbox-header">{{ __('Section editor') }}</h2>
                <div class="inside">
                    <p class="description cms-sections-postbox__lede">{{ __('Select a section from the navigator on the right. Drag to reorder, then edit fields below.') }}</p>

                    <div class="cms-section-canvas" data-section-canvas>
                        @foreach ($page->sections as $section)
                            @php
                                $panelTypeLabel = __($sectionTypes[$section->section_type]['label'] ?? $section->section_type);
                            @endphp
                            <div
                                class="cms-section-panel @if(!$section->is_enabled) cms-section-panel--disabled @endif @if($loop->first) is-active @endif"
                                id="cms-section-panel-{{ $section->id }}"
                                data-section-panel="{{ $section->id }}"
                                @if(!$loop->first) hidden @endif
                            >
                                <div class="cms-section-panel__head">
                                    <div>
                                        <h3 class="cms-section-panel__title">{{ $panelTypeLabel }}</h3>
                                        <p class="cms-section-panel__sub">{{ __('Section') }} #{{ $section->sort_order }}@if(!$section->is_enabled) · {{ __('Disabled') }}@endif</p>
                                    </div>
                                </div>
                                <div class="cms-section-box @if(!$section->is_enabled) cms-section-box--disabled @endif" data-section-id="{{ $section->id }}">
                                    @include('admin.pages.partials.section-form', [
                                        'page' => $page,
                                        'section' => $section,
                                        'sectionTypes' => $sectionTypes,
                                        'sectionRoles' => $sectionRoles,
                                        'formAction' => route('admin.pages.sections.update', [$page, $section]),
                                        'formMethod' => 'PUT',
                                        'submitLabel' => __('Update section'),
                                    ])
                                </div>
                            </div>
                        @endforeach

                        <div
                            class="cms-section-panel cms-section-panel--new"
                            id="cms-section-panel-new"
                            data-section-panel="new"
                            @if($page->sections->isNotEmpty()) hidden @endif
                        >
                            <div class="cms-section-panel__head">
                                <div>
                                    <h3 class="cms-section-panel__title">{{ __('Add section') }}</h3>
                                    <p class="cms-section-panel__sub">{{ __('Choose a block type and placement, then save.') }}</p>
                                </div>
                            </div>
                            <div class="cms-section-box cms-section-box--new">
                                @include('admin.pages.partials.section-form', [
                                    'page' => $page,
                                    'section' => null,
                                    'sectionTypes' => $sectionTypes,
                                    'sectionRoles' => $sectionRoles,
                                    'formAction' => route('admin.pages.sections.store', $page),
                                    'formMethod' => 'POST',
                                    'submitLabel' => __('Add section'),
                                ])
                            </div>
                        </div>

                        @if ($page->sections->isEmpty())
                            <div class="cms-section-canvas__empty" data-section-empty>
                                <span class="dashicons dashicons-welcome-add-page" aria-hidden="true"></span>
                                <p>{{ __('Use the navigator on the right to add your first section.') }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="postbox">
                <h2 class="postbox-header">{{ __('PDFs on this page') }}</h2>
                <div class="inside">
                    <p class="description">{{ __('All PDF links found in this page’s sections and attachments. Edit Forms lists in the Forms catalog section; replace files in') }} <a href="{{ route('admin.media.index', ['type' => 'pdf']) }}">{{ __('Media Library') }}</a>.</p>

                    @if (($page->slug === 'forms' || $page->view_key === 'forms') && ! $page->sections->contains(fn ($s) => $s->section_type === 'forms_catalog'))
                        <div class="notice notice-warning inline">
                            <p>{{ __('Forms PDF list is not CMS-managed yet. Add a “Forms catalog” section (Placement: Main content), or run:') }} <code>php artisan cms:seed-page-sections forms --force</code></p>
                        </div>
                    @endif

                    @if (($pagePdfs ?? []) === [])
                        <p class="description">{{ __('No PDF links on this page yet.') }}</p>
                    @else
                        <table class="widefat striped">
                            <thead>
                                <tr>
                                    <th>{{ __('Label') }}</th>
                                    <th>{{ __('Path') }}</th>
                                    <th>{{ __('Source') }}</th>
                                    <th>{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pagePdfs as $pdf)
                                    <tr>
                                        <td>{{ $pdf['label'] }}</td>
                                        <td><code>{{ $pdf['path'] }}</code></td>
                                        <td>{{ $pdf['source'] }}</td>
                                        <td>
                                            @if (! empty($pdf['path']))
                                                <a href="{{ \App\Support\PublicPath::uploadHref($pdf['path']) }}" target="_blank" rel="noopener">{{ __('Open') }}</a>
                                            @endif
                                            @if (! empty($pdf['media_id']))
                                                · <a href="{{ route('admin.media.index', ['type' => 'pdf', 'folder' => str_starts_with($pdf['path'], 'assets/') ? trim(dirname(\Illuminate\Support\Str::after($pdf['path'], 'assets/')), '/') : '']) }}#media-card-{{ $pdf['media_id'] }}">{{ __('Library') }}</a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>

            <div class="postbox">
                <h2 class="postbox-header">{{ __('Page attachments') }}</h2>
                <div class="inside">
                    <p class="description">{{ __('Optional downloads list appended to the page. Use') }} <a href="{{ route('admin.media.index') }}">{{ __('Media Library') }}</a> {{ __('for inline images/PDFs in sections.') }}</p>

                    @if ($page->media->isNotEmpty())
                        <ul class="admin-list-disc">
                            @foreach ($page->media as $file)
                                <li class="admin-list-item-mb">
                                    <a href="{{ $file->publicUrl() }}" target="_blank" rel="noopener">{{ $file->label }}</a>
                                    <form action="{{ route('admin.pages.media.destroy', [$page, $file]) }}" method="post" class="admin-form-inline" onsubmit='return confirm({{ json_encode(__('Delete this file?')) }});'>
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="button-link-delete button-link-delete">{{ __('Delete') }}</button>
                                    </form>
                                </li>
                            @endforeach
                        </ul>
                    @endif

                    <form method="post" action="{{ route('admin.pages.media.store', $page) }}" enctype="multipart/form-data">
                        @csrf
                        <div class="row cols-2">
                            <div>
                                <label for="file">{{ __('Upload file') }}</label>
                                <input id="file" type="file" name="file" required>
                            </div>
                            <div>
                                <label for="label">{{ __('Title / label') }}</label>
                                <input id="label" type="text" name="label" placeholder="{{ __('Optional') }}">
                            </div>
                        </div>
                        <p class="submit admin-submit-row">
                            <button type="submit" class="button button-primary">{{ __('Upload') }}</button>
                        </p>
                    </form>
                </div>
            </div>

            <p><a href="{{ route('admin.pages.index') }}">← {{ __('All pages') }}</a></p>
        </div>

        <div class="cms-edit-sidebar">
            @include('admin.pages.partials.section-navigator', [
                'page' => $page,
                'sections' => $page->sections,
                'sectionTypes' => $sectionTypes,
                'sectionRoles' => $sectionRoles,
            ])
        </div>
    </div>

    @include('admin.pages.partials.media-picker-modal')
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/trix@2.1.13/dist/trix.min.js"></script>
<script>
    window.cmsAdmin = {
        mediaPickerUrl: @json(route('admin.media.picker')),
        mediaUploadUrl: @json(route('admin.media.store')),
        csrfToken: @json(csrf_token()),
    };
</script>
<script src="{{ asset('assets/js/admin/media-picker.js') }}?v={{ file_exists(public_path('assets/js/admin/media-picker.js')) ? filemtime(public_path('assets/js/admin/media-picker.js')) : 1 }}"></script>
<script src="{{ asset('assets/js/admin/cms-section-editor.js') }}?v={{ file_exists(public_path('assets/js/admin/cms-section-editor.js')) ? filemtime(public_path('assets/js/admin/cms-section-editor.js')) : 1 }}"></script>
@endpush
