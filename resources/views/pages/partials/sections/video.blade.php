@php
    /** @var \App\Services\CmsSectionRegistry $registry */
    /** @var array<string, mixed> $content */
    $videoFile = trim((string) ($content['video_file'] ?? ''));
    $embedUrl = trim((string) ($content['embed_url'] ?? ''));
    $thumbnail = trim((string) ($content['thumbnail'] ?? ''));
    $caption = $registry->transContent($content, 'caption', '');
@endphp
@if ($videoFile !== '' || $embedUrl !== '')
    <section class="laravel-cms-section laravel-cms-section--video">
        @if ($embedUrl !== '')
            <div class="laravel-cms-video__embed">
                <iframe src="{{ $embedUrl }}" title="{{ $caption ?: __('Video') }}" loading="lazy" allowfullscreen></iframe>
            </div>
        @elseif ($videoFile !== '')
            <video class="laravel-cms-video__player" controls @if($thumbnail !== '') poster="{{ $registry->assetUrl($thumbnail) }}" @endif>
                <source src="{{ $registry->assetUrl($videoFile) }}">
            </video>
        @endif
        @if ($caption !== '')
            <p class="laravel-cms-section__caption">{{ $caption }}</p>
        @endif
    </section>
@endif
