@php
    /** @var \App\Services\CmsSectionRegistry $registry */
    /** @var array<string, mixed> $content */
    $heading = $registry->transContent($content, 'heading', '');
    $columns = $registry->transContent($content, 'columns', $content['columns'] ?? []);
    if (! is_array($columns)) {
        $columns = [];
    }
    $rows = (array) ($content['rows'] ?? []);
@endphp
@if ($columns !== [] && $rows !== [])
    <section class="laravel-cms-section laravel-cms-section--table" @if($heading !== '') aria-labelledby="cms-table-{{ $section->id }}" @endif>
        @if ($heading !== '')
            <h2 id="cms-table-{{ $section->id }}" class="laravel-cms-section__heading">{{ $heading }}</h2>
        @endif
        <div class="laravel-cms-table-wrap">
            <table class="laravel-cms-table">
                <thead>
                    <tr>
                        @foreach ($columns as $column)
                            <th scope="col">{{ $column }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach ($rows as $row)
                        @php $cells = is_array($row['cells'] ?? null) ? $row['cells'] : []; @endphp
                        <tr>
                            @foreach ($columns as $index => $column)
                                <td>{{ $cells[$index] ?? '' }}</td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
@endif
