@extends('pages.layouts.structured-page')

@section('structured_meta_title', 'Accounts of Unit Linked Funds - 5th Pillar Takaful')
@section('structured_page_title', 'Accounts of Unit Linked Funds')

@section('structured_hero_title', 'Accounts of Unit Linked Funds')

@section('structured_primary')
    @php
        $statements = [
            [
                'label' => 'December 2025',
                'href' => asset('uploads/2026/2026/04/Unit-Linked-Accounts-Dec-2025-Signed.pdf'),
            ],
            [
                'label' => 'December 2024',
                'href' => asset('uploads/2025/2025/04/Unit-Linked-Accounts-Dec-2024-Signed.pdf'),
            ],
            [
                'label' => 'December 2023',
                'href' => asset('uploads/2024/2024/10/Statement-Of-Unit-Linked-Dec-2023-Signed.pdf'),
            ],
        ];
    @endphp
    <article class="post_item_single page type-page laravel-financial-statements-page">
        <div class="post_content entry-content">
            <h3 class="laravel-financial-statements__heading">Download Accounting Statements</h3>
            <div class="laravel-financial-statements__table-wrap" role="region" aria-label="Unit linked fund accounts">
                <table class="laravel-financial-statements__table">
                    <thead>
                        <tr>
                            <th scope="col">Unit Linked Funds</th>
                            <th scope="col">Download</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($statements as $row)
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
