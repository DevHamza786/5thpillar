@extends('pages.layouts.cms-driven-page')

@section('structured_meta_title', $page->trans('meta_title') ?: ($page->trans('title') . ' - 5th Pillar Family Takaful'))
@section('structured_page_title', $page->trans('title'))

@php
    $isUrdu = app()->getLocale() === 'ur';
    $mastheadBg = $isUrdu
        ? asset('assets/images/inner-banners-2-64d5da6709c98-e1691742724167.webp')
        : asset('assets/images/main-bannner-64d5d132c369d.webp');
@endphp

@section('structured_masthead_bg', $mastheadBg)
