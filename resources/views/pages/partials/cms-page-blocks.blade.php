@php
    /** @var \App\Models\Page|null $page */
@endphp

@if ($page && $page->relationLoaded('sections') && $page->sections->isNotEmpty())
    <div class="laravel-cms-sections">
        @foreach ($page->sections as $section)
            <section class="laravel-cms-section laravel-cms-section--{{ \Illuminate\Support\Str::slug($section->section_type) }}" @if(filled($section->trans('heading'))) aria-labelledby="cms-section-{{ $section->id }}" @endif>
                @if (filled($section->trans('heading')))
                    <h2 id="cms-section-{{ $section->id }}" class="laravel-cms-section__heading">{{ $section->trans('heading') }}</h2>
                @endif
                @if (filled($section->trans('body_html')))
                    <div class="laravel-cms-section__body">
                        {!! \App\Support\PublicPath::rewriteHtmlPublicPaths($section->trans('body_html')) !!}
                    </div>
                @endif
            </section>
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
