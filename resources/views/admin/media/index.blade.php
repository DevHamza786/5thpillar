@extends('admin.layouts.app')

@section('title', __('Media Library'))

@section('content')
    <h1 class="wp-heading-inline">{{ __('Media Library') }}</h1>
    <hr class="wp-header-end">

    <p class="subtitle">{{ __('Central library for images and PDFs stored under public/assets/. Use folders to keep uploads organized.') }}</p>

    <div class="postbox">
        <h2 class="postbox-header">{{ __('Upload file') }}</h2>
        <div class="inside">
            <form method="post" action="{{ route('admin.media.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="row cols-2">
                    <div>
                        <label for="file">{{ __('File') }}</label>
                        <input id="file" type="file" name="file" required accept="image/*,application/pdf">
                    </div>
                    <div>
                        <label for="folder">{{ __('Folder') }}</label>
                        <select id="folder" name="folder" required>
                            <optgroup label="{{ __('Images') }}">
                                @foreach ($imageFolders as $path => $label)
                                    <option value="{{ $path }}" @selected(old('folder', 'images') === $path)>{{ $label }}</option>
                                @endforeach
                            </optgroup>
                            <optgroup label="{{ __('PDFs') }}">
                                @foreach ($pdfFolders as $path => $label)
                                    <option value="{{ $path }}" @selected(old('folder') === $path)>{{ $label }}</option>
                                @endforeach
                            </optgroup>
                        </select>
                    </div>
                </div>
                <div class="row cols-2">
                    <div>
                        <label for="label">{{ __('Title / label') }}</label>
                        <input id="label" type="text" name="label" value="{{ old('label') }}" placeholder="{{ __('Optional') }}" class="large-text">
                    </div>
                    <div>
                        <label for="alt_text">{{ __('Alt text') }} <span class="description">({{ __('images') }})</span></label>
                        <input id="alt_text" type="text" name="alt_text" value="{{ old('alt_text') }}" class="large-text">
                    </div>
                </div>
                <p class="submit admin-submit-row">
                    <button type="submit" class="button button-primary">{{ __('Upload to library') }}</button>
                </p>
            </form>
        </div>
    </div>

    <div class="postbox">
        <h2 class="postbox-header">{{ __('Browse library') }}</h2>
        <div class="inside">
            <form method="get" action="{{ route('admin.media.index') }}" class="tablenav cms-media-filters">
                <label for="type">{{ __('Type') }}</label>
                <select id="type" name="type" onchange="this.form.submit()">
                    <option value="all" @selected($type === 'all')>{{ __('All') }}</option>
                    <option value="image" @selected($type === 'image')>{{ __('Images') }}</option>
                    <option value="pdf" @selected($type === 'pdf')>{{ __('PDFs') }}</option>
                </select>

                <label for="folder">{{ __('Folder') }}</label>
                <select id="folder" name="folder" onchange="this.form.submit()">
                    <option value="">{{ __('All folders') }}</option>
                    <optgroup label="{{ __('Images') }}">
                        @foreach ($imageFolders as $path => $label)
                            <option value="{{ $path }}" @selected($folder === $path)>{{ $label }}</option>
                        @endforeach
                    </optgroup>
                    <optgroup label="{{ __('PDFs') }}">
                        @foreach ($pdfFolders as $path => $label)
                            <option value="{{ $path }}" @selected($folder === $path)>{{ $label }}</option>
                        @endforeach
                    </optgroup>
                </select>
            </form>

            @if ($media->isEmpty())
                <p class="description">{{ __('No files in the library yet.') }}</p>
            @else
                <div class="cms-media-grid">
                    @foreach ($media as $item)
                        <article class="cms-media-card" id="media-card-{{ $item->id }}">
                            <div class="cms-media-card__preview">
                                @if ($item->isImage())
                                    <img src="{{ $item->copyUrl() }}" alt="{{ $item->alt_text ?: $item->label }}" loading="lazy">
                                @elseif ($item->isPdf())
                                    <span class="cms-media-card__pdf-icon dashicons dashicons-pdf" aria-hidden="true"></span>
                                @else
                                    <span class="cms-media-card__file-icon dashicons dashicons-media-default" aria-hidden="true"></span>
                                @endif
                            </div>
                            <div class="cms-media-card__body">
                                <strong class="cms-media-card__title">{{ $item->label ?: $item->original_name }}</strong>
                                <p class="description cms-media-card__meta">
                                    {{ $item->folder ?: __('—') }}
                                    @if ($item->file_size)
                                        · {{ number_format($item->file_size / 1024, 1) }} KB
                                    @endif
                                </p>
                                @if ($item->isPdf())
                                    @php
                                        $usedOn = $pdfUsageByPath[ltrim((string) $item->path, '/')] ?? [];
                                    @endphp
                                    <p class="description cms-media-card__usage">
                                        <strong>{{ __('Used on:') }}</strong>
                                        @if ($usedOn === [])
                                            {{ __('Not linked on any page') }}
                                        @else
                                            @foreach ($usedOn as $i => $pageRef)
                                                @if ($i > 0), @endif
                                                <a href="{{ route('admin.pages.edit', $pageRef['id']) }}">{{ $pageRef['title'] }}</a>
                                            @endforeach
                                        @endif
                                    </p>
                                @endif
                                <p class="cms-media-card__url">
                                    <div class="cms-url-copy">
                                        <input
                                            type="text"
                                            readonly
                                            value="{{ $item->copyUrl() }}"
                                            class="large-text cms-media-copy-input"
                                            id="media-url-{{ $item->id }}"
                                            aria-label="{{ __('File URL') }}"
                                        >
                                        <button
                                            type="button"
                                            class="button button-small cms-copy-url-btn"
                                            data-copy-target="media-url-{{ $item->id }}"
                                            data-copied-label="{{ __('Copied!') }}"
                                        >{{ __('Copy URL') }}</button>
                                    </div>
                                </p>
                                <details class="cms-media-card__edit">
                                    <summary>{{ __('Edit / Replace file') }}</summary>
                                    <form method="post" action="{{ route('admin.media.update', $item) }}" enctype="multipart/form-data" class="admin-media-form-mt">
                                        @csrf
                                        @method('PUT')
                                        <div class="row">
                                            <div>
                                                <label>{{ __('Label') }}</label>
                                                <input type="text" name="label" value="{{ $item->label }}">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div>
                                                <label>{{ __('Alt text') }}</label>
                                                <input type="text" name="alt_text" value="{{ $item->alt_text }}">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div>
                                                <label>{{ __('Replace file') }}</label>
                                                <input type="file" name="file" accept="{{ $item->isPdf() ? 'application/pdf' : 'image/*,application/pdf' }}">
                                                <p class="description">{{ __('Replaces the file at the same path so page links keep working.') }}</p>
                                            </div>
                                        </div>
                                        <p class="admin-flex-row admin-flex-row--mt8">
                                            <button type="submit" class="button button-small">{{ __('Save') }}</button>
                                        </p>
                                    </form>
                                    <form method="post" action="{{ route('admin.media.destroy', $item) }}" class="admin-media-form-mt8" onsubmit='return confirm({{ json_encode(__('Delete this file from disk and library?')) }});'>
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="button button-small button-link-delete">{{ __('Delete') }}</button>
                                    </form>
                                </details>
                            </div>
                        </article>
                    @endforeach
                </div>

                <div class="pager tablenav bottom">
                    {{ $media->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
