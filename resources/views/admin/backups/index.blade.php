@extends('admin.layouts.app')

@section('title', __('Website backups'))

@section('content')
    <h1 class="wp-heading-inline">{{ __('Website backups') }}</h1>
    <hr class="wp-header-end">

    <p class="subtitle">{{ __('Create downloadable ZIP archives with the database and uploaded site assets. Keep copies before major CMS changes.') }}</p>

    <div class="postbox">
        <h2 class="postbox-header">{{ __('Create backup') }}</h2>
        <div class="inside">
            <p class="description">{{ __('Includes: database, public/assets, and storage/app/public. Last :count backups are kept automatically.', ['count' => config('site.backup.retain_count', 10)]) }}</p>
            <form method="post" action="{{ route('admin.backups.store') }}" onsubmit='return confirm({{ json_encode(__('Create a new website backup now?')) }});'>
                @csrf
                <p class="submit admin-submit-row">
                    <button type="submit" class="button button-primary">{{ __('Create backup now') }}</button>
                </p>
            </form>
            <p class="description admin-media-form-mt">{{ __('CLI:') }} <code>php artisan site:backup</code></p>
        </div>
    </div>

    <div class="postbox">
        <h2 class="postbox-header">{{ __('Saved backups') }}</h2>
        <div class="inside">
            @if ($backups === [])
                <p class="description">{{ __('No backups yet. Create your first backup above.') }}</p>
            @else
                <table class="wp-list-table widefat fixed striped">
                    <thead>
                        <tr>
                            <th>{{ __('File') }}</th>
                            <th>{{ __('Size') }}</th>
                            <th>{{ __('Created') }}</th>
                            <th class="admin-col-actions--180">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($backups as $backup)
                            <tr>
                                <td><code>{{ $backup['name'] }}</code></td>
                                <td>{{ $backup['size_label'] }}</td>
                                <td>{{ $backup['created_at'] }}</td>
                                <td>
                                    <a class="button button-small" href="{{ route('admin.backups.download', $backup['name']) }}">{{ __('Download') }}</a>
                                    <form method="post" action="{{ route('admin.backups.destroy', $backup['name']) }}" class="admin-form-inline" onsubmit='return confirm({{ json_encode(__('Delete this backup?')) }});'>
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="button button-small button-link-delete">{{ __('Delete') }}</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
@endsection
