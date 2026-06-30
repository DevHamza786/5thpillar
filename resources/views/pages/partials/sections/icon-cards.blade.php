@php
    /** @var \App\Services\CmsSectionRegistry $registry */
    /** @var array<string, mixed> $content */
    $isUrdu = in_array(app()->getLocale(), ['ur', 'urdu'], true);
    $heading = $registry->transContent($content, 'heading', __('Mission & Vision'));
    $cards = (array) ($content['cards'] ?? []);
@endphp
@if ($cards !== [])
    <section class="laravel-mission-vision">
        <div class="content_wrap">
            @if ($heading !== '')
                <h2 class="laravel-section-title laravel-section-title--mission">{{ $heading }}</h2>
            @endif
            <div class="laravel-mission-vision__grid">
                @foreach ($cards as $card)
                    @php
                        $title = $isUrdu && ! empty($card['title_ur']) ? $card['title_ur'] : ($card['title'] ?? '');
                        $text = $isUrdu && ! empty($card['text_ur']) ? $card['text_ur'] : ($card['text'] ?? '');
                        $icon = $registry->assetUrl((string) ($card['icon'] ?? ''));
                        $iconClass = trim((string) ($card['icon_class'] ?? ''));
                    @endphp
                    <article class="laravel-mv-card">
                        @if ($icon !== '')
                            <div class="laravel-mv-card__icon {{ $iconClass }}">
                                <img src="{{ $icon }}" alt="{{ $title }}" width="124" height="110" loading="lazy" decoding="async">
                            </div>
                        @endif
                        <h3 class="laravel-mv-card__title">{{ $title }}</h3>
                        @if ($text !== '')
                            <p class="laravel-mv-card__text">{{ $text }}</p>
                        @endif
                    </article>
                @endforeach
            </div>
        </div>
    </section>
@endif
