@extends('pages.layouts.structured-page')

@section('structured_meta_title', 'Fund Manager&#039;s Report - 5th Pillar Family Takaful')
@section('structured_page_title', 'Fund Manager&#039;s Report')

@section('structured_hero_title', 'Fund Manager&#039;s Report')

@section('structured_primary')
    @php
        /** Paths under public/uploads (same layout as former wp-content/uploads). */
        $fundManagerReports = [
            ['label' => 'March 2026', 'path' => 'uploads/2026/04/FMR-March-2026.pdf'],
            ['label' => 'February 2026', 'path' => 'uploads/2026/03/FMR-February-2026.pdf'],
            ['label' => 'January 2026', 'path' => 'uploads/2026/02/FMR-January-2026.pdf'],
            ['label' => 'December 2025', 'path' => 'uploads/2026/01/FMR-December-2025-.pdf'],
            ['label' => 'November 2025', 'path' => 'uploads/2025/12/FMR-November-2025.pdf'],
            ['label' => 'October 2025', 'path' => 'uploads/2025/11/FMR-October-2025.pdf'],
            ['label' => 'September 2025', 'path' => 'uploads/2025/10/FMR-September-2025.pdf'],
            ['label' => 'August 2025', 'path' => 'uploads/2025/09/FMR-August-2025-1.pdf'],
            ['label' => 'July 2025', 'path' => 'uploads/2025/09/FMR-July-2025.pdf'],
            ['label' => 'June 2025', 'path' => 'uploads/2025/08/FMR-June-2025.pdf'],
            ['label' => 'May 2025', 'path' => 'uploads/2025/06/FMR-May-2025.pdf'],
            ['label' => 'April 2025', 'path' => 'uploads/2025/05/FMR-April-2025.pdf'],
            ['label' => 'March 2025', 'path' => 'uploads/2025/04/FMR-March-2025.pdf'],
            ['label' => 'February 2025', 'path' => 'uploads/2025/03/FMR-February-2025-2.pdf'],
            ['label' => 'January 2025', 'path' => 'uploads/2025/03/FMR-January-2025-2.pdf'],
            ['label' => 'December 2024', 'path' => 'uploads/2025/01/FMR-December-2024.pdf'],
            ['label' => 'November 2024', 'path' => 'uploads/2025/01/FMR-November-2024-1.pdf'],
            ['label' => 'October 2024', 'path' => 'uploads/2025/01/FMR-October-2024-1.pdf'],
            ['label' => 'September 2024', 'path' => 'uploads/2025/01/FMR-September-2024-1.pdf'],
            ['label' => 'August 2024', 'path' => 'uploads/2025/01/FMR-August-2024-1.pdf'],
            ['label' => 'July 2024', 'path' => 'uploads/2025/01/FMR-July-2024.pdf'],
            ['label' => 'June 2024', 'path' => 'uploads/2025/01/FMR-June-2024.pdf'],
            ['label' => 'May 2024', 'path' => 'uploads/2025/01/FMR-May-2024.pdf'],
            ['label' => 'April 2024', 'path' => 'uploads/2025/01/FMR-April-2024.pdf'],
            ['label' => 'March 2024', 'path' => 'uploads/2025/01/FMR-March-2024.pdf'],
            ['label' => 'February 2024', 'path' => 'uploads/2025/01/FMR-February-2024.pdf'],
            ['label' => 'January 2024', 'path' => 'uploads/2025/01/FMR-January-2024.pdf'],
            ['label' => 'December 2023', 'path' => 'uploads/2025/01/FMR-December-2023.pdf'],
            ['label' => 'November 2023', 'path' => 'uploads/2025/01/FMR-November-2023.pdf'],
            ['label' => 'October 2023', 'path' => 'uploads/2025/01/FR-October-2023.pdf'],
            ['label' => 'September 2023', 'path' => 'uploads/2025/01/FMR-September-2023.pdf'],
        ];
    @endphp
    <article class="post_item_single page type-page laravel-financial-statements-page">
        <div class="post_content entry-content">
            <h3 class="laravel-financial-statements__heading">Fund Manager Report</h3>
            <div class="laravel-financial-statements__table-wrap" role="region" aria-label="Fund manager reports">
                <table class="laravel-financial-statements__table">
                    <thead>
                        <tr>
                            <th scope="col">Fund Manager&#039;s Report</th>
                            <th scope="col">Download</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($fundManagerReports as $row)
                            <tr>
                                <td>{{ $row['label'] }}</td>
                                <td>
                                    <a
                                        class="laravel-financial-statements__link"
                                        href="{{ \App\Support\PublicPath::uploadHref(\App\Support\PublicPath::canonicalUploadPath($row['path'])) }}"
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
