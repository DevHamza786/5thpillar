<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title }} — {{ config('app.name') }}</title>
    @include('layouts.partials.favicon')
    <link rel="stylesheet" href="{{ asset('assets/css/pages/pdf-viewer.css') }}">
</head>
<body>
    <iframe src="{{ $pdfUrl }}#view=FitH" title="{{ $title }}"></iframe>
</body>
</html>
