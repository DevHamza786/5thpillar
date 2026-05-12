document.addEventListener('DOMContentLoaded', function () {
    var dropZone = document.getElementById('drop-zone');
    var fileInput = document.getElementById('file-input');
    var form = document.getElementById('upload-form');
    var submitBtn = document.getElementById('submit-btn');
    var fileSelected = document.getElementById('file-selected');
    var filenameDisplay = document.getElementById('filename-display');
    var loadingOverlay = document.getElementById('loading-overlay');

    if (!dropZone || !fileInput || !form) {
        return;
    }

    dropZone.addEventListener('click', function () {
        fileInput.click();
    });

    ['dragenter', 'dragover'].forEach(function (eventName) {
        dropZone.addEventListener(
            eventName,
            function (e) {
                e.preventDefault();
                dropZone.classList.add('dragging');
            },
            false
        );
    });

    ['dragleave', 'drop'].forEach(function (eventName) {
        dropZone.addEventListener(
            eventName,
            function (e) {
                e.preventDefault();
                dropZone.classList.remove('dragging');
            },
            false
        );
    });

    dropZone.addEventListener('drop', function (e) {
        var dt = e.dataTransfer;
        if (dt && dt.files && dt.files.length) {
            fileInput.files = dt.files;
            handleFileSelect();
        }
    });

    fileInput.addEventListener('change', handleFileSelect);

    function handleFileSelect() {
        if (fileInput.files.length > 0) {
            var file = fileInput.files[0];
            if (filenameDisplay) {
                filenameDisplay.textContent = file.name;
            }
            if (fileSelected) {
                fileSelected.classList.remove('hidden');
            }
            if (submitBtn) {
                submitBtn.disabled = false;
            }
            dropZone.classList.add('border-teal-500', 'bg-teal-50');
        }
    }

    form.addEventListener('submit', function () {
        if (loadingOverlay) {
            loadingOverlay.style.display = 'flex';
        }
    });
});
