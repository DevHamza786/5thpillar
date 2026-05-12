@php
    /** @var \App\Models\NavMenuItem $item */
@endphp

<div class="row cols-2">
    <div>
        <label for="parent_id">{{ __('Parent item') }}</label>
        <select id="parent_id" name="parent_id">
            @foreach ($parentOptions as $value => $label)
                <option value="{{ $value }}" @selected(old('parent_id', $item->parent_id) == $value)>{{ $label }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label for="sort_order">{{ __('Order') }}</label>
        <input id="sort_order" type="number" name="sort_order" value="{{ old('sort_order', $item->sort_order ?? 0) }}" min="0">
        <p class="description">{{ __('Among siblings; lower numbers appear first.') }}</p>
    </div>
</div>

<div class="row">
    <div>
        <label for="label">{{ __('Label') }}</label>
        <input id="label" type="text" name="label" value="{{ old('label', $item->label) }}" required class="large-text">
    </div>
</div>

<div class="row">
    <div>
        <label for="link_type">{{ __('Link type') }}</label>
        <select id="link_type" name="link_type">
            @foreach ([
                \App\Models\NavMenuItem::LINK_NONE => __('Group only (opens submenu)'),
                \App\Models\NavMenuItem::LINK_HOME => __('Home page'),
                \App\Models\NavMenuItem::LINK_PAGE_SLUG => __('Internal page (slug)'),
                \App\Models\NavMenuItem::LINK_NAMED_ROUTE => __('Named route (e.g. news listing)'),
                \App\Models\NavMenuItem::LINK_CUSTOM_URL => __('Custom URL or path'),
                \App\Models\NavMenuItem::LINK_MEDIA => __('Uploaded file (PDF / image)'),
            ] as $type => $ltLabel)
                <option value="{{ $type }}" @selected(old('link_type', $item->link_type ?? \App\Models\NavMenuItem::LINK_NONE) === $type)>{{ $ltLabel }}</option>
            @endforeach
        </select>
    </div>
</div>

<div class="row field-link field-page_slug" id="wrap-page_slug">
    <div>
        <label for="page_slug">{{ __('Page slug') }}</label>
        <input id="page_slug" type="text" name="page_slug" value="{{ old('page_slug', $item->page_slug) }}" placeholder="about-us" pattern="[a-z0-9]+(-[a-z0-9]+)*">
    </div>
</div>

<div class="row field-link field-route_name" id="wrap-route_name">
    <div>
        <label for="route_name">{{ __('Route name') }}</label>
        <input id="route_name" type="text" name="route_name" value="{{ old('route_name', $item->route_name) }}" placeholder="news-events.index">
        <p class="description">{{ __('Use news-events.index for News & Events.') }}</p>
    </div>
</div>

<div class="row field-link field-custom_url" id="wrap-custom_url">
    <div>
        <label for="custom_url">{{ __('URL') }}</label>
        <input id="custom_url" type="text" name="custom_url" value="{{ old('custom_url', $item->custom_url) }}" placeholder="https://… or /path">
    </div>
</div>

<div class="row field-link field-cms_media_id" id="wrap-cms_media_id">
    <div>
        <label for="cms_media_id">{{ __('Uploaded file') }}</label>
        <select id="cms_media_id" name="cms_media_id">
            <option value="">—</option>
            @foreach ($mediaOptions as $mid => $mlabel)
                <option value="{{ $mid }}" @selected(old('cms_media_id', $item->cms_media_id) == $mid)>{{ $mlabel }}</option>
            @endforeach
        </select>
        <p class="description">{!! __('Upload more under <a href=":url">Navigation → Upload files</a>.', ['url' => route('admin.navigation.media.index')]) !!}</p>
    </div>
</div>

<p class="check">
    <input id="open_new_tab" type="checkbox" name="open_new_tab" value="1" @checked(old('open_new_tab', $item->open_new_tab ?? false))>
    <label for="open_new_tab">{{ __('Open in new tab') }}</label>
</p>

@push('scripts')
    <script>
        (function () {
            var sel = document.getElementById('link_type');
            if (!sel) return;
            function sync() {
                var v = sel.value;
                document.querySelectorAll('.field-link').forEach(function (el) {
                    el.style.display = 'none';
                });
                if (v === 'page_slug') document.getElementById('wrap-page_slug').style.display = '';
                if (v === 'named_route') document.getElementById('wrap-route_name').style.display = '';
                if (v === 'custom_url') document.getElementById('wrap-custom_url').style.display = '';
                if (v === 'media') document.getElementById('wrap-cms_media_id').style.display = '';
            }
            sel.addEventListener('change', sync);
            sync();
        })();
    </script>
@endpush
