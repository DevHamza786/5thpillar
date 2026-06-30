@php
    /** @var \App\Services\CmsSectionRegistry $registry */
    /** @var array<string, mixed> $content */
    $heading = $registry->transContent($content, 'heading', '');
    $subheading = $registry->transContent($content, 'subheading', '');
    $body = $registry->transContent($content, 'content', '');
    $buttonText = $registry->transContent($content, 'button_text', '');
    $buttonUrl = trim((string) ($content['button_url'] ?? ''));
@endphp
<section class="laravel-cms-section laravel-cms-section--text" @if($heading !== '') aria-labelledby="cms-text-{{ $section->id }}" @endif>
    @if ($heading !== '')
        <h2 id="cms-text-{{ $section->id }}" class="laravel-cms-section__heading">{{ $heading }}</h2>
    @endif
    @if ($subheading !== '')
        <p class="laravel-cms-section__subheading">{{ $subheading }}</p>
    @endif
    @if ($body !== '')
        <div class="laravel-cms-section__body">{!! nl2br(e($body)) !!}</div>
    @endif
    @if ($buttonText !== '' && $buttonUrl !== '')
        <p class="laravel-cms-section__actions">
            <a href="{{ $buttonUrl }}" class="laravel-cms-section__button">{{ $buttonText }}</a>
        </p>
    @endif
</section>
