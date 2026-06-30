@php
    /** @var \App\Services\CmsSectionRegistry $registry */
    /** @var array<string, mixed> $content */
    $enabled = filter_var($content['enabled'] ?? true, FILTER_VALIDATE_BOOLEAN);
    $image = $registry->assetUrl((string) ($content['image'] ?? ''));
    $alt = $registry->transContent($content, 'alt', '');
    $ariaLabel = $registry->transContent($content, 'aria_label', __('Home announcement'));
@endphp
@if ($enabled && $image !== '')
    <div class="laravel-home-popup" data-home-popup role="dialog" aria-modal="true" aria-label="{{ $ariaLabel }}" hidden>
        <div class="laravel-home-popup__dialog" data-home-popup-dialog>
            <button type="button" class="laravel-home-popup__close" data-home-popup-close aria-label="{{ __('Close popup') }}">&times;</button>
            <img
                class="laravel-home-popup__image"
                src="{{ $image }}"
                width="810"
                height="672"
                alt="{{ $alt }}"
                decoding="async"
            >
        </div>
    </div>
@endif
