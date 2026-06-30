(function () {
    var config = window.cmsAdmin || {};
    var modal = document.getElementById('cms-media-picker');
    if (!modal || !config.mediaPickerUrl) {
        return;
    }

    var grid = modal.querySelector('[data-media-picker-grid]');
    var typeSelect = document.getElementById('cms-media-picker-type');
    var uploadForm = modal.querySelector('[data-media-picker-upload]');
    var folderSelect = modal.querySelector('[data-media-picker-folder]');
    var fileInput = document.getElementById('cms-media-picker-file');
    var uploadStatus = modal.querySelector('[data-media-picker-upload-status]');
    var uploadBtn = modal.querySelector('[data-media-picker-upload-btn]');
    var activeInput = null;

    function currentType() {
        return typeSelect ? typeSelect.value : 'image';
    }

    function syncFolderOptions() {
        if (!folderSelect) {
            return;
        }

        var type = currentType();
        var firstVisible = null;

        Array.prototype.forEach.call(folderSelect.options, function (option) {
            var assetType = option.getAttribute('data-asset-type') || 'image';
            var visible = assetType === type;
            option.hidden = !visible;
            option.disabled = !visible;
            if (visible && firstVisible === null) {
                firstVisible = option;
            }
        });

        if (firstVisible) {
            folderSelect.value = firstVisible.value;
        }

        if (fileInput) {
            fileInput.accept = type === 'pdf' ? 'application/pdf,.pdf' : 'image/*';
        }
    }

    function setUploadStatus(message, isError) {
        if (!uploadStatus) {
            return;
        }

        if (!message) {
            uploadStatus.hidden = true;
            uploadStatus.textContent = '';
            uploadStatus.classList.remove('is-error');
            return;
        }

        uploadStatus.hidden = false;
        uploadStatus.textContent = message;
        uploadStatus.classList.toggle('is-error', !!isError);
    }

    function openPicker(input, type) {
        activeInput = input;
        if (typeSelect) {
            typeSelect.value = type || 'image';
        }
        syncFolderOptions();
        setUploadStatus('');
        if (uploadForm) {
            uploadForm.reset();
        }
        syncFolderOptions();
        modal.hidden = false;
        modal.setAttribute('aria-hidden', 'false');
        loadItems();
    }

    function closePicker() {
        modal.hidden = true;
        modal.setAttribute('aria-hidden', 'true');
        activeInput = null;
        setUploadStatus('');
    }

    function selectItem(item) {
        if (activeInput) {
            activeInput.value = item.path || '';
            activeInput.dispatchEvent(new Event('input', { bubbles: true }));
        }
        closePicker();
    }

    function renderItem(item, type) {
        var button = document.createElement('button');
        button.type = 'button';
        button.className = 'cms-media-picker__item';
        button.title = item.label || item.path;

        if (type === 'pdf') {
            button.innerHTML = '<span class="dashicons dashicons-pdf"></span><span>' + (item.label || 'PDF') + '</span>';
        } else {
            var img = document.createElement('img');
            img.src = item.url;
            img.alt = item.alt_text || item.label || '';
            img.loading = 'lazy';
            button.appendChild(img);
        }

        button.addEventListener('click', function () {
            selectItem(item);
        });

        return button;
    }

    function loadItems(selectPath) {
        if (!grid) {
            return;
        }

        var type = currentType();
        grid.innerHTML = '<p class="description">Loading…</p>';

        fetch(config.mediaPickerUrl + '?type=' + encodeURIComponent(type), {
            headers: { Accept: 'application/json' },
            credentials: 'same-origin',
        })
            .then(function (response) {
                return response.json();
            })
            .then(function (payload) {
                var items = payload.data || [];
                if (!items.length) {
                    grid.innerHTML = '<p class="description">No files found. Upload a file above.</p>';
                    return;
                }

                grid.innerHTML = '';
                items.forEach(function (item) {
                    var button = renderItem(item, type);
                    grid.appendChild(button);
                    if (selectPath && item.path === selectPath) {
                        button.classList.add('is-selected');
                    }
                });
            })
            .catch(function () {
                grid.innerHTML = '<p class="description">Could not load media library.</p>';
            });
    }

    function uploadFile(event) {
        event.preventDefault();

        if (!uploadForm || !config.mediaUploadUrl || !fileInput || !fileInput.files.length) {
            return;
        }

        var formData = new FormData(uploadForm);
        setUploadStatus('Uploading…', false);
        if (uploadBtn) {
            uploadBtn.disabled = true;
        }

        fetch(config.mediaUploadUrl, {
            method: 'POST',
            headers: {
                Accept: 'application/json',
                'X-CSRF-TOKEN': config.csrfToken || '',
            },
            credentials: 'same-origin',
            body: formData,
        })
            .then(function (response) {
                return response.json().then(function (payload) {
                    return { ok: response.ok, payload: payload };
                });
            })
            .then(function (result) {
                if (!result.ok) {
                    var message = 'Upload failed.';
                    if (result.payload && result.payload.message) {
                        message = result.payload.message;
                    } else if (result.payload && result.payload.errors) {
                        message = Object.values(result.payload.errors).flat().join(' ');
                    }
                    throw new Error(message);
                }

                var item = result.payload.data;
                setUploadStatus(result.payload.message || 'File uploaded.', false);
                uploadForm.reset();
                syncFolderOptions();
                loadItems(item ? item.path : null);

                if (item && activeInput) {
                    selectItem(item);
                }
            })
            .catch(function (error) {
                setUploadStatus(error.message || 'Upload failed.', true);
            })
            .finally(function () {
                if (uploadBtn) {
                    uploadBtn.disabled = false;
                }
            });
    }

    document.addEventListener('click', function (event) {
        var pickBtn = event.target.closest('.cms-media-pick-btn');
        if (pickBtn) {
            var wrapper = pickBtn.closest('.cms-url-copy') || pickBtn.parentElement;
            var input = wrapper ? wrapper.querySelector('.cms-media-path-input, input[type="text"]') : null;
            if (input) {
                openPicker(input, pickBtn.getAttribute('data-media-type') || 'image');
            }
            return;
        }

        if (event.target.matches('[data-media-picker-close]')) {
            closePicker();
        }
    });

    if (typeSelect) {
        typeSelect.addEventListener('change', function () {
            syncFolderOptions();
            loadItems();
        });
    }

    if (uploadForm) {
        uploadForm.addEventListener('submit', uploadFile);
    }

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape' && !modal.hidden) {
            closePicker();
        }
    });
})();
