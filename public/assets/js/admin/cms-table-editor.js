(function () {
    var config = window.cmsTableEditor || {};
    var body = document.getElementById('cms-spreadsheet-body');
    var template = document.getElementById('cms-table-row-template');
    var addBtn = document.getElementById('cms-table-add-row');
    var pasteToggle = document.getElementById('cms-table-paste-toggle');
    var pastePanel = document.getElementById('cms-table-paste-panel');
    var pasteInput = document.getElementById('cms-table-paste-input');
    var pasteApply = document.getElementById('cms-table-paste-apply');

    if (!body || !template) {
        return;
    }

    function reindexRows() {
        body.querySelectorAll('[data-row]').forEach(function (row, index) {
            row.querySelectorAll('input, select, textarea').forEach(function (input) {
                if (!input.name) {
                    return;
                }
                input.name = input.name.replace(/rows\[\d+\]/, 'rows[' + index + ']');
            });
        });
    }

    function addRow(values) {
        var index = body.querySelectorAll('[data-row]').length;
        var html = template.innerHTML.replace(/__INDEX__/g, String(index));
        var wrapper = document.createElement('tbody');
        wrapper.innerHTML = html.trim();
        var row = wrapper.firstElementChild;
        body.appendChild(row);

        if (values && Array.isArray(values)) {
            var inputs = row.querySelectorAll('input[type="text"], input[type="number"]');
            inputs.forEach(function (input, i) {
                if (values[i] !== undefined) {
                    input.value = values[i];
                }
            });
        }

        reindexRows();
    }

    if (addBtn) {
        addBtn.addEventListener('click', function () {
            addRow();
        });
    }

    body.addEventListener('click', function (event) {
        if (event.target.matches('[data-row-remove]')) {
            event.target.closest('[data-row]').remove();
            reindexRows();
        }
    });

    if (pasteToggle && pastePanel) {
        pasteToggle.addEventListener('click', function () {
            pastePanel.hidden = !pastePanel.hidden;
        });
    }

    if (pasteApply && pasteInput) {
        pasteApply.addEventListener('click', function () {
            var text = pasteInput.value.trim();
            if (!text) {
                return;
            }

            text.split(/\r?\n/).forEach(function (line) {
                if (!line.trim()) {
                    return;
                }
                var parts = line.indexOf('\t') >= 0 ? line.split('\t') : line.split(',');
                addRow(parts.map(function (part) { return part.trim(); }));
            });

            pasteInput.value = '';
            pastePanel.hidden = true;
        });
    }
})();
