@extends('admin.layouts.app')

@section('title', __('Menu files'))

@section('content')
    <h1 class="wp-heading-inline">{{ __('Files for navigation') }}</h1>
    <a href="{{ route('admin.navigation.index') }}" class="page-title-action">{{ __('← Menu items') }}</a>
    <a href="{{ route('admin.media.index') }}" class="page-title-action">{{ __('Media Library') }}</a>
    <hr class="wp-header-end">

    <p class="subtitle">{{ __('Upload PDFs or images here, then pick them when editing a menu item (link type: Uploaded file). For site-wide assets, use') }} <a href="{{ route('admin.media.index') }}">{{ __('Media Library') }}</a>.</p>

    <div class="postbox">
        <h2 class="postbox-header">{{ __('Upload') }}</h2>
        <div class="inside">
            <form method="post" action="{{ route('admin.navigation.media.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="row cols-2">
                    <div>
                        <label for="file">{{ __('File') }}</label>
                        <input id="file" type="file" name="file" required>
                    </div>
                    <div>
                        <label for="label">{{ __('Label') }}</label>
                        <input id="label" type="text" name="label" placeholder="{{ __('Optional') }}">
                    </div>
                </div>
                <p class="submit admin-submit-row">
                    <button type="submit" class="button button-primary">{{ __('Upload') }}</button>
                </p>
            </form>
        </div>
    </div>

    <table class="wp-list-table widefat fixed striped">
        <thead>
            <tr>
                <th>{{ __('Label') }}</th>
                <th>{{ __('URL') }}</th>
                <th>{{ __('MIME') }}</th>
                <th class="admin-col-actions--140">{{ __('Actions') }}</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($files as $file)
                @php $copyUrl = $file->copyUrl(); @endphp
                <tr>
                    <td>{{ $file->label ?? $file->original_name }}</td>
                    <td>
                        <div class="cms-url-copy">
                            <input
                                type="text"
                                readonly
                                value="{{ $copyUrl }}"
                                class="large-text cms-media-copy-input"
                                id="nav-media-url-{{ $file->id }}"
                                aria-label="{{ __('File URL') }}"
                            >
                            <button
                                type="button"
                                class="button button-small cms-copy-url-btn"
                                data-copy-target="nav-media-url-{{ $file->id }}"
                                data-copied-label="{{ __('Copied!') }}"
                            >{{ __('Copy URL') }}</button>
                        </div>
                        <p class="description admin-description-tight">
                            <a href="{{ $file->publicUrl() }}" target="_blank" rel="noopener">{{ __('Open file') }}</a>
                        </p>
                    </td>
                    <td>{{ $file->mime }}</td>
                    <td>
                        <form action="{{ route('admin.navigation.media.destroy', $file) }}" method="post" class="admin-form-inline" onsubmit='return confirm({{ json_encode(__('Delete this file?')) }});'>
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="button button-small button-link-delete">{{ __('Delete') }}</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="4" class="description">{{ __('No standalone files yet. Upload a PDF above.') }}</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="pager tablenav bottom">{{ $files->links() }}</div>
@endsection
