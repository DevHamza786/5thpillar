@php
    /** @var \App\Services\CmsSectionRegistry $registry */
    /** @var array<string, mixed> $content */
    /** @var array<string, mixed> $settings */
    $title = $registry->transContent($content, 'title', '');
    $columnLabel = $registry->transContent($content, 'column_label', 'Document');
    $downloadLabel = $registry->transContent($content, 'download_label', 'Click Here');
    $rows = (array) ($content['rows'] ?? []);
    $wrapperClass = $settings['wrapper_class'] ?? 'laravel-financial-statements-page';
    $isUrdu = in_array(app()->getLocale(), ['ur', 'urdu'], true);
@endphp
<article class="post_item_single page type-page {{ $wrapperClass }}">
    <div class="post_content entry-content">
        @if (filled($title))
            <h3 class="laravel-financial-statements__heading">{{ $title }}</h3>
        @endif
        <div class="laravel-financial-statements__table-wrap" role="region" aria-label="{{ $columnLabel }}">
            <table class="laravel-financial-statements__table">
                <thead>
                    <tr>
                        <th scope="col">{{ $columnLabel }}</th>
                        <th scope="col">{{ __('Download') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($rows as $row)
                        @php
                            $rowLabel = $isUrdu && ! empty($row['label_ur']) ? $row['label_ur'] : ($row['label'] ?? '');
                            $rowPath = (string) ($row['path'] ?? '');
                            $rowHref = $rowPath !== '' ? \App\Support\PublicPath::uploadHref($rowPath) : '#';
                        @endphp
                        <tr>
                            <td>{{ $rowLabel }}</td>
                            <td>
                                @if ($rowPath !== '')
                                    <a
                                        class="laravel-financial-statements__link"
                                        href="{{ $rowHref }}"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                    >{{ $downloadLabel }}</a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2">{{ __('No documents available.') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</article>
