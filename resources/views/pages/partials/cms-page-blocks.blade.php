@php
    /** @var \App\Models\Page|null $page */
    $previewMode = ! empty($cmsPreview);
    $renderer = app(\App\Services\CmsSectionRenderer::class);
    $sections = isset($page) ? $renderer->appendSections($page) : collect();

    if ($previewMode && isset($page) && $page->relationLoaded('sections')) {
        $sections = $page->sections
            ->filter(fn ($section) => ($section->settings['role'] ?? 'append') !== 'primary')
            ->filter(fn ($section) => $previewMode || $section->is_enabled)
            ->sortBy('sort_order')
            ->values();
    }
@endphp

@if ($page && $sections->isNotEmpty())
    <div class="laravel-cms-sections">
        @foreach ($sections as $section)
            @if ($previewMode && ! $section->is_enabled)
                <p class="laravel-cms-section__preview-note">{{ __('Disabled section (preview only)') }}</p>
            @endif
            {!! $renderer->render($section) !!}
        @endforeach
    </div>
@endif

@if ($page && $page->relationLoaded('media') && $page->media->isNotEmpty())
    <div class="laravel-cms-downloads">
        <h2 class="laravel-cms-downloads__heading">{{ __('Downloads') }}</h2>

        <ul class="laravel-cms-downloads__list">
            @foreach ($page->media as $file)
                <li>
                    <a href="{{ $file->publicUrl() }}" target="_blank" rel="noopener noreferrer">
                        {{ $file->label ?? $file->original_name ?? 'Download' }}
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
@endif
