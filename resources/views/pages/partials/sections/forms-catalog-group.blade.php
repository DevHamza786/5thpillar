@php
    /** @var array<string, mixed> $group */
    /** @var bool $isUrdu */
    /** @var bool $forceAccordion */
    $heading = $isUrdu && ! empty($group['heading_ur']) ? $group['heading_ur'] : ($group['heading'] ?? '');
    $style = ($group['style'] ?? 'plain');
    $open = filter_var($group['open'] ?? true, FILTER_VALIDATE_BOOLEAN);
    $items = (array) ($group['items'] ?? []);
    $useAccordion = $forceAccordion || $style === 'accordion';
@endphp

@if ($useAccordion && $style === 'accordion')
    <details class="laravel-forms-accordion__item" @if($open) open @endif>
        <summary class="laravel-forms-accordion__summary">{{ $heading }}</summary>
        <div class="laravel-forms-accordion__body">
            <ul class="laravel-forms-page__list laravel-forms-page__list--in-accordion">
                @foreach ($items as $item)
                    @php
                        $itemLabel = $isUrdu && ! empty($item['label_ur']) ? $item['label_ur'] : ($item['label'] ?? '');
                        $itemPath = (string) ($item['path'] ?? '');
                    @endphp
                    @if ($itemPath !== '')
                        <li>
                            <a href="{{ \App\Support\PublicPath::uploadHref($itemPath) }}" target="_blank" rel="noopener noreferrer">{{ $itemLabel }}</a>
                        </li>
                    @endif
                @endforeach
            </ul>
        </div>
    </details>
@else
    @if (filled($heading))
        <p class="laravel-forms-page__claim-label"><strong>{{ $heading }}</strong></p>
    @endif
    <ul class="laravel-forms-page__list">
        @foreach ($items as $item)
            @php
                $itemLabel = $isUrdu && ! empty($item['label_ur']) ? $item['label_ur'] : ($item['label'] ?? '');
                $itemPath = (string) ($item['path'] ?? '');
            @endphp
            @if ($itemPath !== '')
                <li>
                    <a href="{{ \App\Support\PublicPath::uploadHref($itemPath) }}" target="_blank" rel="noopener noreferrer">{{ $itemLabel }}</a>
                </li>
            @endif
        @endforeach
    </ul>
@endif
