(function () {
    function initBilingualTabs(root) {
        root.querySelectorAll('[data-bilingual-tabs]').forEach(function (tabs) {
            tabs.querySelectorAll('[data-tab-target]').forEach(function (button) {
                button.addEventListener('click', function () {
                    var target = button.getAttribute('data-tab-target');
                    tabs.querySelectorAll('[data-tab-target]').forEach(function (btn) {
                        btn.classList.toggle('is-active', btn === button);
                    });
                    tabs.querySelectorAll('[data-tab-panel]').forEach(function (panel) {
                        panel.classList.toggle('is-active', panel.getAttribute('data-tab-panel') === target);
                    });
                });
            });
        });
    }

    function toggleSectionFields(form, type) {
        form.querySelectorAll('[data-section-fields]').forEach(function (block) {
            block.hidden = block.getAttribute('data-section-fields') !== type;
        });
    }

    function initSectionTypeSelects(root) {
        root.querySelectorAll('[data-section-type-select]').forEach(function (select) {
            var form = select.closest('[data-section-form]');
            if (!form) {
                return;
            }

            var apply = function () {
                toggleSectionFields(form, select.value);
            };

            select.addEventListener('change', apply);
            apply();
        });
    }

    function initPdfRows(root) {
        root.addEventListener('click', function (event) {
            if (event.target.matches('[data-pdf-row-add]')) {
                var container = event.target.closest('.cms-section-fields--pdf-table');
                if (!container) {
                    return;
                }

                var rowsWrap = container.querySelector('[data-pdf-rows]');
                var template = document.getElementById('cms-pdf-row-template');
                if (!rowsWrap || !template) {
                    return;
                }

                var index = rowsWrap.querySelectorAll('[data-pdf-row]').length;
                var html = template.innerHTML.replace(/__INDEX__/g, String(index));
                var wrapper = document.createElement('div');
                wrapper.innerHTML = html.trim();
                rowsWrap.appendChild(wrapper.firstElementChild);
                return;
            }

            if (event.target.matches('[data-pdf-row-remove]')) {
                var row = event.target.closest('[data-pdf-row]');
                if (row) {
                    row.remove();
                }
            }
        });
    }

    function initCmsRepeatRows(root) {
        root.addEventListener('click', function (event) {
            if (event.target.matches('[data-cms-row-add]')) {
                var button = event.target;
                var fields = button.closest('[data-section-fields]');
                var rowsWrap = fields ? fields.querySelector('[data-cms-rows]') : null;
                var templateId = rowsWrap ? rowsWrap.getAttribute('data-cms-row-template') : null;
                var template = templateId ? document.getElementById(templateId) : null;
                if (!rowsWrap || !template) {
                    return;
                }
                var index = rowsWrap.querySelectorAll('[data-cms-row]').length;
                var html = template.innerHTML.replace(/__INDEX__/g, String(index));
                var wrapper = document.createElement('div');
                wrapper.innerHTML = html.trim();
                rowsWrap.appendChild(wrapper.firstElementChild);
                return;
            }

            if (event.target.matches('[data-cms-row-remove]')) {
                var row = event.target.closest('[data-cms-row]');
                if (row) {
                    row.remove();
                }
            }
        });
    }

    function initHomeSlotToggle(root) {
        root.querySelectorAll('[data-section-form]').forEach(function (form) {
            var roleSelect = form.querySelector('[data-section-role-select]');
            var slotRow = form.querySelector('[data-home-slot-row]');
            if (!roleSelect || !slotRow) {
                return;
            }
            var apply = function () {
                slotRow.hidden = roleSelect.value !== 'home';
            };
            roleSelect.addEventListener('change', apply);
            apply();
        });
    }

    function selectSection(editor, sectionId) {
        var canvas = editor.querySelector('[data-section-canvas]');
        var navItems = editor.querySelectorAll('.cms-navigator__item[data-section-id]');
        var panels = editor.querySelectorAll('[data-section-panel]');
        var emptyState = editor.querySelector('[data-section-empty]');

        navItems.forEach(function (item) {
            var active = String(item.getAttribute('data-section-id')) === String(sectionId);
            item.classList.toggle('is-active', active);
            var btn = item.querySelector('[data-section-select]');
            if (btn) {
                btn.setAttribute('aria-current', active ? 'true' : 'false');
            }
        });

        panels.forEach(function (panel) {
            var active = String(panel.getAttribute('data-section-panel')) === String(sectionId);
            panel.hidden = !active;
            panel.classList.toggle('is-active', active);
        });

        if (emptyState) {
            emptyState.hidden = true;
        }

        editor.setAttribute('data-active-section', String(sectionId));
    }

    function syncPanelOrder(editor) {
        var list = editor.querySelector('#cms-sections-sortable');
        var canvas = editor.querySelector('[data-section-canvas]');
        if (!list || !canvas) {
            return;
        }

        var newPanel = canvas.querySelector('[data-section-panel="new"]');
        list.querySelectorAll('.cms-navigator__item[data-section-id]').forEach(function (item) {
            var sectionId = item.getAttribute('data-section-id');
            var panel = canvas.querySelector('[data-section-panel="' + sectionId + '"]');
            if (panel) {
                if (newPanel) {
                    canvas.insertBefore(panel, newPanel);
                } else {
                    canvas.appendChild(panel);
                }
            }
        });
    }

    function updateNavigatorCount(editor) {
        var count = editor.querySelectorAll('.cms-navigator__item[data-section-id]').length;
        var badge = editor.querySelector('.cms-navigator__count');
        if (badge) {
            badge.textContent = String(count);
        }
    }

    function saveOrder(editor) {
        var list = editor.querySelector('#cms-sections-sortable');
        if (!list) {
            return;
        }

        var reorderUrl = list.getAttribute('data-reorder-url');
        if (!reorderUrl || !window.cmsAdmin) {
            return;
        }

        var order = Array.from(list.querySelectorAll('.cms-navigator__item[data-section-id]')).map(function (item) {
            return parseInt(item.getAttribute('data-section-id'), 10);
        });

        fetch(reorderUrl, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': window.cmsAdmin.csrfToken || '',
            },
            credentials: 'same-origin',
            body: JSON.stringify({ order: order }),
        });
    }

    function initNavigator(editor) {
        var addButtons = editor.querySelectorAll('[data-section-add]');
        var navList = editor.querySelector('#cms-sections-sortable');
        var canvas = editor.querySelector('[data-section-canvas]');
        if (!navList || !canvas) {
            return;
        }

        var dragged = null;

        addButtons.forEach(function (button) {
            button.addEventListener('click', function () {
                selectSection(editor, 'new');
            });
        });

        navList.addEventListener('click', function (event) {
            var selectBtn = event.target.closest('[data-section-select]');
            if (!selectBtn) {
                return;
            }
            selectSection(editor, selectBtn.getAttribute('data-section-select'));
        });

        navList.querySelectorAll('.cms-navigator__item[data-section-id]').forEach(function (item) {
            item.addEventListener('dragstart', function (event) {
                dragged = item;
                item.classList.add('is-dragging');
                if (event.dataTransfer) {
                    event.dataTransfer.effectAllowed = 'move';
                    event.dataTransfer.setData('text/plain', item.getAttribute('data-section-id') || '');
                }
            });

            item.addEventListener('dragend', function () {
                item.classList.remove('is-dragging');
                dragged = null;
                syncPanelOrder(editor);
                saveOrder(editor);
            });

            item.addEventListener('dragover', function (event) {
                event.preventDefault();
                if (!dragged || dragged === item) {
                    return;
                }

                var rect = item.getBoundingClientRect();
                var after = event.clientY > rect.top + rect.height / 2;
                navList.insertBefore(dragged, after ? item.nextSibling : item);
            });
        });

        var firstItem = navList.querySelector('.cms-navigator__item[data-section-id]');
        if (firstItem) {
            selectSection(editor, firstItem.getAttribute('data-section-id'));
        } else {
            selectSection(editor, 'new');
        }
    }

    function initPageEditor() {
        document.querySelectorAll('[data-cms-page-editor]').forEach(function (editor) {
            initNavigator(editor);
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        initBilingualTabs(document);
        initSectionTypeSelects(document);
        initPdfRows(document);
        initCmsRepeatRows(document);
        initHomeSlotToggle(document);
        initPageEditor();
    });
})();
