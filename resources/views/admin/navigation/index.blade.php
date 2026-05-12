@extends('admin.layouts.app')

@section('title', __('Navigation'))

@section('content')
    <h1 class="wp-heading-inline">{{ __('Menus') }}</h1>
    <a href="{{ route('admin.navigation.create') }}" class="page-title-action">{{ __('Add menu item') }}</a>
    <a href="{{ route('admin.navigation.media.index') }}" class="page-title-action">{{ __('Upload files') }}</a>
    <hr class="wp-header-end" style="border:0;border-top:1px solid #c3c4c7;margin:12px 0 16px;">

    <p class="subtitle">{{ __('Control header links: internal pages, custom URLs, or PDFs/images uploaded for the menu. Drag order is controlled by the “Order” column (lower numbers first).') }}</p>

    <div class="notice notice-info">
        <p>{{ __('After the first database seed, your previous hard-coded menu is copied here. You can change any PDF link to use “Uploaded file” once you upload under Upload files.') }}</p>
    </div>

    <table class="wp-list-table widefat fixed striped">
        <thead>
            <tr>
                <th scope="col">{{ __('Label') }}</th>
                <th scope="col">{{ __('Parent') }}</th>
                <th scope="col">{{ __('Link') }}</th>
                <th scope="col" style="width:70px;">{{ __('Order') }}</th>
                <th scope="col" style="width:120px;">{{ __('Actions') }}</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($items as $row)
                <tr>
                    <td><strong>{{ $row->label }}</strong></td>
                    <td class="description">{{ $row->parent?->label ?? '—' }}</td>
                    <td>
                        @switch($row->link_type)
                            @case(\App\Models\NavMenuItem::LINK_HOME)
                                <code>{{ __('Home') }}</code>
                                @break
                            @case(\App\Models\NavMenuItem::LINK_PAGE_SLUG)
                                <code>/{{ $row->page_slug }}</code>
                                @break
                            @case(\App\Models\NavMenuItem::LINK_NAMED_ROUTE)
                                <code>{{ $row->route_name }}</code>
                                @break
                            @case(\App\Models\NavMenuItem::LINK_CUSTOM_URL)
                                <span class="description" style="word-break:break-all;">{{ \Illuminate\Support\Str::limit($row->custom_url, 60) }}</span>
                                @break
                            @case(\App\Models\NavMenuItem::LINK_MEDIA)
                                {{ __('File') }} #{{ $row->cms_media_id }}
                                @break
                            @default
                                <span class="description">{{ __('— Group / dropdown —') }}</span>
                        @endswitch
                        @if ($row->open_new_tab)
                            <span class="description">({{ __('new tab') }})</span>
                        @endif
                    </td>
                    <td>{{ $row->sort_order }}</td>
                    <td>
                        <a class="button button-small" href="{{ route('admin.navigation.edit', $row) }}">{{ __('Edit') }}</a>
                        <form action="{{ route('admin.navigation.destroy', $row) }}" method="post" style="display:inline;" onsubmit='return confirm({{ json_encode(__('Delete this item and its children?')) }});'>
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="button button-small button-link-delete">{{ __('Delete') }}</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="description">{{ __('No items yet. Run db:seed or add your first link.') }}</td></tr>
            @endforelse
        </tbody>
    </table>
@endsection
