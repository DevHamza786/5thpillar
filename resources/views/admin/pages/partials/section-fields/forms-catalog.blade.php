@php
    $isEn = $locale === 'en';
    $columns = old('content.columns', $content['columns'] ?? []);
    if (! is_array($columns) || $columns === []) {
        $columns = app(\App\Services\CmsSectionRegistry::class)->defaultContent('forms_catalog')['columns'] ?? [];
    }
@endphp
<div class="cms-section-fields cms-section-fields--forms-catalog" data-section-fields="forms_catalog" @if($currentType !== 'forms_catalog') hidden @endif>
    @if ($isEn)
        <p class="description">{{ __('Manage the Forms page PDF lists. Use Pick PDF to choose a file from Media Library, or paste an assets/pdf/… path.') }}</p>

        @foreach ($columns as $colIndex => $column)
            @php
                $groups = is_array($column['groups'] ?? null) ? $column['groups'] : [];
            @endphp
            <div class="cms-forms-column" data-forms-column>
                <h4 class="admin-heading-sm--spaced">{{ __('Column') }} {{ $colIndex + 1 }}</h4>
                <div class="row cols-2">
                    <div>
                        <label>{{ __('Column title') }}</label>
                        <input type="text" name="content[columns][{{ $colIndex }}][title]" value="{{ $column['title'] ?? '' }}" class="large-text">
                    </div>
                    <div>
                        <label>{{ __('Column title (Urdu)') }}</label>
                        <input type="text" name="content[columns][{{ $colIndex }}][title_ur]" value="{{ $column['title_ur'] ?? '' }}" class="large-text" dir="rtl">
                    </div>
                </div>

                <div class="cms-forms-groups" data-forms-groups data-column-index="{{ $colIndex }}">
                    @foreach ($groups as $groupIndex => $group)
                        @php
                            $items = is_array($group['items'] ?? null) ? $group['items'] : [];
                        @endphp
                        <div class="cms-forms-group postbox" data-forms-group>
                            <div class="inside">
                                <div class="row cols-2">
                                    <div>
                                        <label>{{ __('Group heading') }}</label>
                                        <input type="text" name="content[columns][{{ $colIndex }}][groups][{{ $groupIndex }}][heading]" value="{{ $group['heading'] ?? '' }}">
                                    </div>
                                    <div>
                                        <label>{{ __('Group heading (Urdu)') }}</label>
                                        <input type="text" name="content[columns][{{ $colIndex }}][groups][{{ $groupIndex }}][heading_ur]" value="{{ $group['heading_ur'] ?? '' }}" dir="rtl">
                                    </div>
                                </div>
                                <div class="row cols-2">
                                    <div>
                                        <label>{{ __('Style') }}</label>
                                        <select name="content[columns][{{ $colIndex }}][groups][{{ $groupIndex }}][style]">
                                            <option value="plain" @selected(($group['style'] ?? 'plain') === 'plain')>{{ __('Plain list') }}</option>
                                            <option value="accordion" @selected(($group['style'] ?? '') === 'accordion')>{{ __('Accordion') }}</option>
                                        </select>
                                    </div>
                                    <div>
                                        <p class="check admin-check-mt">
                                            <input type="hidden" name="content[columns][{{ $colIndex }}][groups][{{ $groupIndex }}][open]" value="0">
                                            <input
                                                id="forms-open-{{ $colIndex }}-{{ $groupIndex }}"
                                                type="checkbox"
                                                name="content[columns][{{ $colIndex }}][groups][{{ $groupIndex }}][open]"
                                                value="1"
                                                @checked(filter_var($group['open'] ?? true, FILTER_VALIDATE_BOOLEAN))
                                            >
                                            <label for="forms-open-{{ $colIndex }}-{{ $groupIndex }}">{{ __('Open by default') }}</label>
                                        </p>
                                    </div>
                                </div>

                                <h5 class="admin-heading-sm--spaced">{{ __('PDF forms') }}</h5>
                                <div class="cms-forms-items" data-forms-items>
                                    @foreach ($items as $itemIndex => $item)
                                        <div class="cms-forms-item" data-forms-item>
                                            <div class="row cols-2">
                                                <div>
                                                    <label>{{ __('Label') }}</label>
                                                    <input type="text" name="content[columns][{{ $colIndex }}][groups][{{ $groupIndex }}][items][{{ $itemIndex }}][label]" value="{{ $item['label'] ?? '' }}">
                                                </div>
                                                <div>
                                                    <label>{{ __('Label (Urdu)') }}</label>
                                                    <input type="text" name="content[columns][{{ $colIndex }}][groups][{{ $groupIndex }}][items][{{ $itemIndex }}][label_ur]" value="{{ $item['label_ur'] ?? '' }}" dir="rtl">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div>
                                                    <label>{{ __('PDF path') }}</label>
                                                    <div class="cms-url-copy">
                                                        <input type="text" name="content[columns][{{ $colIndex }}][groups][{{ $groupIndex }}][items][{{ $itemIndex }}][path]" value="{{ $item['path'] ?? '' }}" class="large-text cms-media-path-input" placeholder="assets/pdf/forms/…">
                                                        <button type="button" class="button button-small cms-media-pick-btn" data-media-type="pdf">{{ __('Pick PDF') }}</button>
                                                    </div>
                                                </div>
                                            </div>
                                            <p class="admin-paragraph-mb">
                                                <button type="button" class="button button-small button-link-delete" data-forms-item-remove>{{ __('Remove form') }}</button>
                                            </p>
                                        </div>
                                    @endforeach
                                </div>
                                <p class="admin-submit-row">
                                    <button type="button" class="button" data-forms-item-add>{{ __('Add form') }}</button>
                                    <button type="button" class="button button-link-delete" data-forms-group-remove>{{ __('Remove group') }}</button>
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
                <p class="admin-submit-row">
                    <button type="button" class="button" data-forms-group-add>{{ __('Add group') }}</button>
                </p>
            </div>
        @endforeach

        @if ($columns === [])
            <p class="description">{{ __('No columns yet. Seed the Forms page or add a forms_catalog section with default structure.') }}</p>
        @endif

        <template id="cms-forms-group-template">
            <div class="cms-forms-group postbox" data-forms-group>
                <div class="inside">
                    <div class="row cols-2">
                        <div>
                            <label>{{ __('Group heading') }}</label>
                            <input type="text" name="content[columns][__COL__][groups][__GINDEX__][heading]" value="">
                        </div>
                        <div>
                            <label>{{ __('Group heading (Urdu)') }}</label>
                            <input type="text" name="content[columns][__COL__][groups][__GINDEX__][heading_ur]" value="" dir="rtl">
                        </div>
                    </div>
                    <div class="row cols-2">
                        <div>
                            <label>{{ __('Style') }}</label>
                            <select name="content[columns][__COL__][groups][__GINDEX__][style]">
                                <option value="plain">{{ __('Plain list') }}</option>
                                <option value="accordion" selected>{{ __('Accordion') }}</option>
                            </select>
                        </div>
                        <div>
                            <p class="check admin-check-mt">
                                <input type="hidden" name="content[columns][__COL__][groups][__GINDEX__][open]" value="0">
                                <input id="forms-open-__COL__-__GINDEX__" type="checkbox" name="content[columns][__COL__][groups][__GINDEX__][open]" value="1" checked>
                                <label for="forms-open-__COL__-__GINDEX__">{{ __('Open by default') }}</label>
                            </p>
                        </div>
                    </div>
                    <h5 class="admin-heading-sm--spaced">{{ __('PDF forms') }}</h5>
                    <div class="cms-forms-items" data-forms-items></div>
                    <p class="admin-submit-row">
                        <button type="button" class="button" data-forms-item-add>{{ __('Add form') }}</button>
                        <button type="button" class="button button-link-delete" data-forms-group-remove>{{ __('Remove group') }}</button>
                    </p>
                </div>
            </div>
        </template>

        <template id="cms-forms-item-template">
            <div class="cms-forms-item" data-forms-item>
                <div class="row cols-2">
                    <div>
                        <label>{{ __('Label') }}</label>
                        <input type="text" name="content[columns][__COL__][groups][__GINDEX__][items][__INDEX__][label]" value="">
                    </div>
                    <div>
                        <label>{{ __('Label (Urdu)') }}</label>
                        <input type="text" name="content[columns][__COL__][groups][__GINDEX__][items][__INDEX__][label_ur]" value="" dir="rtl">
                    </div>
                </div>
                <div class="row">
                    <div>
                        <label>{{ __('PDF path') }}</label>
                        <div class="cms-url-copy">
                            <input type="text" name="content[columns][__COL__][groups][__GINDEX__][items][__INDEX__][path]" value="" class="large-text cms-media-path-input" placeholder="assets/pdf/forms/…">
                            <button type="button" class="button button-small cms-media-pick-btn" data-media-type="pdf">{{ __('Pick PDF') }}</button>
                        </div>
                    </div>
                </div>
                <p class="admin-paragraph-mb">
                    <button type="button" class="button button-small button-link-delete" data-forms-item-remove>{{ __('Remove form') }}</button>
                </p>
            </div>
        </template>
    @else
        <p class="description">{{ __('Edit Urdu labels in the English tab (each row has Label Urdu). Column/group titles also have Urdu fields there.') }}</p>
    @endif
</div>
