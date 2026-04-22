@extends('pages.layouts.structured-page')

@section('structured_meta_title', 'Financial Statements - 5th Pillar Family Takaful Limited')
@section('structured_page_title', 'Financial Statements')

@section('structured_hero_title', 'Financial Statements')

@section('structured_primary')
    @php
        $annualReports = [
            [
                'label' => 'Annual Report 2025',
                'href' => asset('uploads/2026/2026/04/Annual-Report-2025.pdf'),
            ],
            [
                'label' => 'Annual Report 2024',
                'href' => asset('uploads/2025/2025/04/Annual-Report-2024-1.pdf'),
            ],
            [
                'label' => 'Annual Report 2023',
                'href' => asset('uploads/2024/2024/04/Annual-Report_compressed-2023.pdf'),
            ],
            [
                'label' => 'Annual Report 2022',
                'href' => asset('uploads/2023/2023/10/5PTFL-Accounts-Dec-2022-For-Publishing.pdf'),
            ],
            [
                'label' => 'Annual Report 2021',
                'href' => asset('uploads/2023/2023/08/FS-Dec-2021-Signed-Audit-Report.pdf'),
            ],
        ];
    @endphp
    <article class="post_item_single page type-page laravel-financial-statements-page">
        <div class="post_content entry-content">
            <h3 class="laravel-financial-statements__heading">Download Our Annual Report</h3>
            <div class="laravel-financial-statements__table-wrap" role="region" aria-label="Annual reports">
                <table class="laravel-financial-statements__table">
                    <thead>
                        <tr>
                            <th scope="col">Annual Report</th>
                            <th scope="col">Download</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($annualReports as $row)
                            <tr>
                                <td>{{ $row['label'] }}</td>
                                <td>
                                    <a
                                        class="laravel-financial-statements__link"
                                        href="{{ $row['href'] }}"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                    >Click Here</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </article>
@endsection
