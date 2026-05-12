<script>
    (function () {
        var modal = document.getElementById('laravel-brochure-modal');
        if (!modal) return;

        var keyInput = document.getElementById('brochure_key');
        var form = modal.querySelector('.laravel-brochure-form');
        var lastBrochureKey = 'haazir';

        function setOpen(open) {
            modal.classList.toggle('is-open', open);
            modal.setAttribute('aria-hidden', open ? 'false' : 'true');
            document.body.style.overflow = open ? 'hidden' : '';
        }

        function clearJsErrors() {
            var box = document.getElementById('laravel-brochure-js-errors');
            if (box) box.remove();
        }

        function showJsErrors(errors, message) {
            clearJsErrors();
            var wrap = document.createElement('div');
            wrap.id = 'laravel-brochure-js-errors';
            wrap.className = 'laravel-brochure-modal__alert';
            wrap.setAttribute('role', 'alert');
            var ul = document.createElement('ul');
            ul.className = 'laravel-brochure-modal__errors';
            if (message) {
                var li0 = document.createElement('li');
                li0.textContent = message;
                ul.appendChild(li0);
            }
            Object.keys(errors || {}).forEach(function (k) {
                (errors[k] || []).forEach(function (msg) {
                    var li = document.createElement('li');
                    li.textContent = msg;
                    ul.appendChild(li);
                });
            });
            wrap.appendChild(ul);
            var title = modal.querySelector('.laravel-brochure-modal__title');
            if (title) {
                title.insertAdjacentElement('afterend', wrap);
            } else {
                modal.querySelector('.laravel-brochure-modal__panel').prepend(wrap);
            }
        }

        document.querySelectorAll('.laravel-brochure-trigger').forEach(function (btn) {
            btn.addEventListener('click', function () {
                var k = btn.getAttribute('data-brochure-key');
                if (keyInput && k) keyInput.value = k;
                lastBrochureKey = k || 'haazir';
                clearJsErrors();
                setOpen(true);
            });
        });

        modal.querySelectorAll('.laravel-brochure-close').forEach(function (el) {
            el.addEventListener('click', function () {
                setOpen(false);
            });
        });

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && modal.classList.contains('is-open')) setOpen(false);
        });

        if (form) {
            form.addEventListener('submit', function (e) {
                e.preventDefault();
                clearJsErrors();

                var submitBtn = form.querySelector('[type="submit"]');
                if (submitBtn) submitBtn.disabled = true;

                var fd = new FormData(form);

                fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        Accept: 'application/json',
                    },
                    body: fd,
                })
                    .then(function (res) {
                        return res.text().then(function (text) {
                            var data = {};
                            try {
                                data = text ? JSON.parse(text) : {};
                            } catch (ignore) {
                                data = { message: text ? text.slice(0, 200) : 'Invalid response' };
                            }
                            return { ok: res.ok, status: res.status, data: data };
                        });
                    })
                    .then(function (result) {
                        if (submitBtn) submitBtn.disabled = false;

                        if (result.ok && result.data && result.data.pdf_url) {
                            window.open(result.data.pdf_url, '_blank', 'noopener,noreferrer');
                            setOpen(false);
                            form.reset();
                            if (keyInput) keyInput.value = lastBrochureKey;
                            return;
                        }

                        if (result.status === 422 && result.data && result.data.errors) {
                            showJsErrors(result.data.errors, result.data.message || '');
                            return;
                        }

                        var msg =
                            (result.data && (result.data.message || result.data.brochure)) ||
                            'Something went wrong. Please try again.';
                        showJsErrors({}, msg);
                    })
                    .catch(function () {
                        if (submitBtn) submitBtn.disabled = false;
                        showJsErrors({}, 'Network error. Please try again.');
                    });
            });
        }
    })();
</script>
