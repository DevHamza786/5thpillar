@php
    /**
     * Reusable Google Play / App Store download buttons for the Niyyat app.
     * Reuses the gold store badge SVGs shared with the site footer.
     *
     * @var string|null $variant  Optional modifier suffix, e.g. 'onlight' | 'ondark'.
     */
    $googlePlayUrl = 'https://play.google.com/store/apps/details?id=com.fifthpillartakaful.niyyat&hl=en';
    $appStoreUrl = 'https://apps.apple.com/in/app/5th-pillar-niyyat/id6596748529';
    $variantClass = isset($variant) && $variant !== '' ? ' laravel-app-downloads--'.$variant : '';
@endphp

<div class="laravel-app-downloads{{ $variantClass }}">
    <a class="laravel-app-download" href="{{ $googlePlayUrl }}" target="_blank" rel="noopener noreferrer" aria-label="{{ __('Download on Google Play') }}">
        <img src="{{ asset('assets/images/footer/Google.png') }}" alt="{{ __('Download on Google Play') }}" width="187" height="54" loading="lazy" decoding="async">
    </a>
    <a class="laravel-app-download" href="{{ $appStoreUrl }}" target="_blank" rel="noopener noreferrer" aria-label="{{ __('Download on the App Store') }}">
        <img src="{{ asset('assets/images/footer/App.png') }}" alt="{{ __('Download on the App Store') }}" width="163" height="54" loading="lazy" decoding="async">
    </a>
</div>
