@extends('pages.layouts.structured-page')

@section('structured_meta_title', 'Fund Price Archive 2026 - 5th Pillar Family Takaful')
@section('structured_page_title', 'Fund Price Archive 2026')

@section('structured_hero_title', 'Fund Price Archive 2026')

@section('structured_primary')
    @include('pages.partials.fund-archive-accordion', ['year' => 2026])
@endsection
