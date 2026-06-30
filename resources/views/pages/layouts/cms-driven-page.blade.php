@extends('pages.layouts.structured-page')

@section('structured_primary')
    @include('pages.partials.cms-primary-sections', ['page' => $page ?? null])
@endsection

@section('structured_tertiary')
    @include('pages.partials.cms-tertiary-sections', ['page' => $page ?? null])
@endsection
