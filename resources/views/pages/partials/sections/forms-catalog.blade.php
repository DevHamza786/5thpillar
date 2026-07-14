@php
    /** @var \App\Services\CmsSectionRegistry $registry */
    /** @var array<string, mixed> $content */
    $columns = (array) ($content['columns'] ?? []);
    $isUrdu = in_array(app()->getLocale(), ['ur', 'urdu'], true);
@endphp
<article class="post_item_single post_type_page page type-page status-publish hentry laravel-forms-page">
    <div class="post_content entry-content">
        <section class="wpb-content-wrapper">
            <div class="vc_row wpb_row vc_row-fluid laravel-forms-page__row">
                @foreach ($columns as $column)
                    @php
                        $columnTitle = $isUrdu && ! empty($column['title_ur']) ? $column['title_ur'] : ($column['title'] ?? '');
                        $groups = (array) ($column['groups'] ?? []);
                        $hasAccordion = collect($groups)->contains(fn ($g) => is_array($g) && ($g['style'] ?? '') === 'accordion');
                    @endphp
                    <div class="wpb_column vc_column_container vc_col-sm-6 sc_layouts_column_icons_position_left">
                        <div class="vc_column-inner">
                            <div class="wpb_wrapper">
                                @if (filled($columnTitle))
                                    <h2 class="laravel-forms-page__title">{{ $columnTitle }}</h2>
                                @endif

                                @if ($hasAccordion)
                                    <div class="laravel-forms-accordion" role="presentation">
                                        @foreach ($groups as $group)
                                            @include('pages.partials.sections.forms-catalog-group', [
                                                'group' => $group,
                                                'isUrdu' => $isUrdu,
                                                'forceAccordion' => true,
                                            ])
                                        @endforeach
                                    </div>
                                @else
                                    @foreach ($groups as $group)
                                        @include('pages.partials.sections.forms-catalog-group', [
                                            'group' => $group,
                                            'isUrdu' => $isUrdu,
                                            'forceAccordion' => false,
                                        ])
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    </div>
</article>
