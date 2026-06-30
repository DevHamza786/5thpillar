@php
    /** @var \App\Services\CmsSectionRegistry $registry */
    /** @var array<string, mixed> $content */
    $isUrdu = in_array(app()->getLocale(), ['ur', 'urdu'], true);
    $lead = $registry->transContent($content, 'lead', '');
    $items = (array) ($content['items'] ?? []);
@endphp
<section class="laravel-about-wp-intro" aria-label="{{ __('About 5th Pillar Family Takaful') }}">
    <div class="laravel-about-wp-intro__row">
        <div class="laravel-about-wp-intro__col laravel-about-wp-intro__col--main">
            @if ($lead !== '')
                <p class="laravel-about-wp-intro__text">{{ $lead }}</p>
            @endif
            @if ($items !== [])
                <ul class="laravel-about-wp-intro__list">
                    @foreach ($items as $item)
                        @php
                            $text = $isUrdu && ! empty($item['text_ur']) ? $item['text_ur'] : ($item['text'] ?? '');
                        @endphp
                        @if ($text !== '')
                            <li>{{ $text }}</li>
                        @endif
                    @endforeach
                </ul>
            @endif
        </div>
        <div class="laravel-about-wp-intro__col laravel-about-wp-intro__col--aside" aria-hidden="true"></div>
    </div>
</section>
