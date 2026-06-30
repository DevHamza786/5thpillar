@php
    $__brochureModalJs = public_path('assets/js/laravel-brochure-modal.js');
    $__brochureModalVer = is_file($__brochureModalJs) ? (string) filemtime($__brochureModalJs) : '1';
@endphp
<script src="{{ asset('assets/js/laravel-brochure-modal.js') }}?v={{ $__brochureModalVer }}" defer></script>
