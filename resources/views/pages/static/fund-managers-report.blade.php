@extends('pages.layouts.structured-page')

@section('structured_body_class', 'laravel-fund-managers-report-page')

@section('structured_meta_title', 'Fund Manager&#039;s Report - 5th Pillar Family Takaful')
@section('structured_page_title', 'Fund Manager&#039;s Report')

@section('structured_hero_title', 'Fund Manager&#039;s Report')

@section('structured_primary')
    @php
        $renderer = app(\App\Services\CmsSectionRenderer::class);
        $cmsPrimary = $renderer->primarySection($page ?? null, 'pdf_table');
    @endphp
    @if ($cmsPrimary)
        {!! $renderer->render($cmsPrimary) !!}
    @else
        @php $fundManagerReports = \App\Support\FundManagersReportRepository::reports(); @endphp
        <article class="post_item_single page type-page laravel-financial-statements-page">
            <div class="post_content entry-content">
                <div class="laravel-financial-statements__table-wrap" role="region" aria-label="Fund manager reports">
                    <table class="laravel-financial-statements__table">
                        <thead>
                            <tr>
                                <th scope="col">Fund Manager&#039;s Report</th>
                                <th scope="col">Download</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($fundManagerReports as $row)
                                <tr>
                                    <td>{{ $row['label'] }}</td>
                                    <td>
                                        <a class="laravel-financial-statements__link" href="{{ \App\Support\PublicPath::uploadHref($row['path']) }}" target="_blank" rel="noopener noreferrer">Click Here</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2">Fund manager reports are not available at the moment.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </article>
    @endif
@endsection
