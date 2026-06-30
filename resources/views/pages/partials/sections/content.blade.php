@php
    /** @var \App\Models\PageSection $section */
    /** @var \App\Services\CmsSectionRegistry $registry */
@endphp
<section class="laravel-cms-section laravel-cms-section--content" @if(filled($section->trans('heading'))) aria-labelledby="cms-section-{{ $section->id }}" @endif>
    @if (filled($section->trans('heading')))
        <h2 id="cms-section-{{ $section->id }}" class="laravel-cms-section__heading">{{ $section->trans('heading') }}</h2>
    @endif
    @if (filled($section->trans('body_html')))
        <div class="laravel-cms-section__body">
            {!! \App\Support\PublicPath::rewriteHtmlPublicPaths($section->trans('body_html')) !!}
        </div>
    @endif
</section>
