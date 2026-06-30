@php
    /** @var \App\Models\Page $page */
    /** @var \Illuminate\Support\Collection|\App\Models\PageSection[] $sections */
    /** @var array<string, array<string, mixed>> $sectionTypes */
    /** @var array<string, string> $sectionRoles */
    $homeSlots = config('cms.home_slots', []);
@endphp
<aside class="cms-navigator" data-cms-navigator aria-label="{{ __('Section navigator') }}">
    <div class="cms-navigator__header">
        <h3 class="cms-navigator__title">
            <span class="dashicons dashicons-list-view" aria-hidden="true"></span>
            {{ __('Navigator') }}
        </h3>
        <span class="cms-navigator__count">{{ $sections->count() }}</span>
    </div>

    <ul
        class="cms-navigator__list"
        id="cms-sections-sortable"
        data-reorder-url="{{ route('admin.pages.sections.reorder', $page) }}"
    >
        @forelse ($sections as $section)
            @php
                $typeLabel = __($sectionTypes[$section->section_type]['label'] ?? $section->section_type);
                $role = (string) ($section->settings['role'] ?? 'append');
                $roleLabel = __($sectionRoles[$role] ?? $role);
                if ($role === 'home') {
                    $slot = (string) ($section->settings['slot'] ?? '');
                    $roleLabel = __($homeSlots[$slot] ?? $slot ?: __('Homepage'));
                }
            @endphp
            <li
                class="cms-navigator__item @if(!$section->is_enabled) cms-navigator__item--disabled @endif @if($loop->first) is-active @endif"
                data-section-id="{{ $section->id }}"
                draggable="true"
            >
                <span class="cms-navigator__drag dashicons dashicons-menu" aria-hidden="true" title="{{ __('Drag to reorder') }}"></span>
                <button
                    type="button"
                    class="cms-navigator__btn"
                    data-section-select="{{ $section->id }}"
                    aria-controls="cms-section-panel-{{ $section->id }}"
                    @if($loop->first) aria-current="true" @endif
                >
                    <span class="cms-navigator__btn-main">
                        <span class="cms-navigator__type">{{ $typeLabel }}</span>
                        @if (!$section->is_enabled)
                            <span class="cms-navigator__badge cms-navigator__badge--off">{{ __('Off') }}</span>
                        @endif
                    </span>
                    <span class="cms-navigator__meta">{{ $roleLabel }}</span>
                </button>
            </li>
        @empty
            <li class="cms-navigator__empty">{{ __('No sections yet') }}</li>
        @endforelse
    </ul>

    <button type="button" class="cms-navigator__add" data-section-add>
        <span class="dashicons dashicons-plus-alt2" aria-hidden="true"></span>
        {{ __('Add section') }}
    </button>
</aside>
