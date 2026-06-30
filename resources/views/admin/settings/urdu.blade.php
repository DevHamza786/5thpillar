@extends('admin.layouts.app')

@section('title', __('Urdu URLs'))

@section('content')
    <h1 class="wp-heading-inline">{{ __('Urdu URLs') }}</h1>
    <hr class="wp-header-end">

    <p class="subtitle">{{ __('Control the Urdu site prefix, system page paths, and review CMS page Urdu slugs.') }}</p>

    <form method="post" action="{{ route('admin.settings.urdu.update') }}">
        @csrf
        @method('PUT')

        <div class="postbox">
            <h2 class="postbox-header">{{ __('Urdu site prefix') }}</h2>
            <div class="inside">
                <p class="check">
                    <input type="hidden" name="enabled" value="0">
                    <input id="urdu_enabled" type="checkbox" name="enabled" value="1" @checked(old('enabled', $locale['enabled'] ?? true))>
                    <label for="urdu_enabled">{{ __('Enable Urdu version of the website') }}</label>
                </p>
                <div class="row cols-2">
                    <div>
                        <label for="urdu_prefix">{{ __('URL prefix') }}</label>
                        <input id="urdu_prefix" type="text" name="prefix" value="{{ old('prefix', $locale['prefix'] ?? 'urdu') }}" class="large-text" required pattern="[a-z0-9]+(-[a-z0-9]+)*" placeholder="urdu">
                        <p class="description">{{ __('English home stays at / — Urdu home becomes /:prefix', ['prefix' => old('prefix', $locale['prefix'] ?? 'urdu')]) }}</p>
                    </div>
                    <div>
                        <label>{{ __('Preview') }}</label>
                        <p><code>{{ url('/'.old('prefix', $locale['prefix'] ?? 'urdu')) }}</code></p>
                        <p class="description">{{ __('Use lowercase letters, numbers, and hyphens only.') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="postbox">
            <h2 class="postbox-header">{{ __('System page paths (under Urdu prefix)') }}</h2>
            <div class="inside">
                <p class="description">{{ __('Customize Urdu URL segments for built-in pages. Leave blank to use the English slug.') }}</p>
                <table class="wp-list-table widefat fixed striped">
                    <thead>
                        <tr>
                            <th>{{ __('Page') }}</th>
                            <th>{{ __('English path') }}</th>
                            <th>{{ __('Urdu path segment') }}</th>
                            <th>{{ __('Full Urdu URL') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($routeDefinitions as $route)
                            @php
                                $urduSegment = old('route_slugs.'.$route['key'], $locale['route_slugs'][$route['key']] ?? $route['default']);
                            @endphp
                            <tr>
                                <td><strong>{{ __($route['label']) }}</strong></td>
                                <td><code>/{{ $route['default'] }}</code></td>
                                <td>
                                    <input type="text" name="route_slugs[{{ $route['key'] }}]" value="{{ $urduSegment }}" class="large-text" dir="auto">
                                </td>
                                <td><code>{{ url('/'.($locale['prefix'] ?? 'urdu').'/'.($urduSegment ?: $route['default'])) }}</code></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <p class="submit">
            <button type="submit" class="button button-primary">{{ __('Save Urdu URL settings') }}</button>
        </p>
    </form>

    <div class="postbox admin-postbox-mt">
        <h2 class="postbox-header">{{ __('CMS page Urdu slugs') }}</h2>
        <div class="inside">
            <p class="description">{{ __('Set a custom Urdu slug on each page from the page editor (Urdu tab). If empty, the English slug is reused under the Urdu prefix.') }}</p>
            @if ($pages->isEmpty())
                <p>{{ __('No pages yet.') }}</p>
            @else
                <table class="wp-list-table widefat fixed striped">
                    <thead>
                        <tr>
                            <th>{{ __('Page') }}</th>
                            <th>{{ __('English URL') }}</th>
                            <th>{{ __('Urdu URL') }}</th>
                            <th>{{ __('EN') }}</th>
                            <th>{{ __('UR') }}</th>
                            <th class="admin-col-actions--100">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pages as $page)
                            @php
                                $urduSlug = filled($page->slug_ur) ? $page->slug_ur : $page->slug;
                            @endphp
                            <tr>
                                <td>
                                    <strong>{{ $page->title }}</strong>
                                </td>
                                <td><code>{{ url('/'.$page->slug) }}</code></td>
                                <td><code dir="rtl">{{ url('/'.($locale['prefix'] ?? 'urdu').'/'.$urduSlug) }}</code></td>
                                <td>
                                    <span class="cms-status cms-status--{{ $page->status === 'published' ? 'published' : 'draft' }}">{{ __($page->status) }}</span>
                                </td>
                                <td>
                                    <span class="cms-status cms-status--{{ ($page->status_ur ?? 'draft') === 'published' ? 'published' : 'draft' }}">{{ __($page->status_ur ?? 'draft') }}</span>
                                </td>
                                <td>
                                    <a class="button button-small" href="{{ route('admin.pages.edit', $page) }}">{{ __('Edit') }}</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
@endsection
