@extends('admin.layouts.app')

@section('title', __('Menu files'))

@section('content')
    <h1 class="wp-heading-inline">{{ __('Files for navigation') }}</h1>
    <a href="{{ route('admin.navigation.index') }}" class="page-title-action">{{ __('← Menu items') }}</a>
    <hr class="wp-header-end" style="border:0;border-top:1px solid #c3c4c7;margin:12px 0 16px;">

    <p class="subtitle">{{ __('Upload PDFs or images here, then pick them when editing a menu item (link type: Uploaded file).') }}</p>

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
                <p class="submit" style="margin:0;">
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
                <th style="width:100px;">{{ __('Actions') }}</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($files as $file)
                <tr>
                    <td>{{ $file->label ?? $file->original_name }}</td>
                    <td class="description" style="word-break:break-all;"><a href="{{ $file->publicUrl() }}" target="_blank" rel="noopener">{{ \Illuminate\Support\Str::limit($file->publicUrl(), 70) }}</a></td>
                    <td>{{ $file->mime }}</td>
                    <td>
                        <form action="{{ route('admin.navigation.media.destroy', $file) }}" method="post" style="display:inline;" onsubmit='return confirm({{ json_encode(__('Delete this file?')) }});'>
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
