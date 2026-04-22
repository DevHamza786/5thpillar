<script>
    (function () {
        var modal = document.getElementById('laravel-brochure-modal');
        if (!modal) return;

        var keyInput = document.getElementById('brochure_key');

        function setOpen(open) {
            modal.classList.toggle('is-open', open);
            modal.setAttribute('aria-hidden', open ? 'false' : 'true');
            document.body.style.overflow = open ? 'hidden' : '';
        }

        document.querySelectorAll('.laravel-brochure-trigger').forEach(function (btn) {
            btn.addEventListener('click', function () {
                var k = btn.getAttribute('data-brochure-key');
                if (keyInput && k) keyInput.value = k;
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
    })();
</script>
