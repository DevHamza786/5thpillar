@extends('pages.layouts.structured-page')

@section('structured_meta_title', 'Fund Price Archive 2023 - 5th Pillar Family Takaful')
@section('structured_page_title', 'Fund Price Archive 2023')

@section('structured_hero_title', 'Fund Price Archive 2023')

@section('structured_primary')
    @include('pages.partials.fund-archive-accordion', ['year' => 2023])
@endsection
