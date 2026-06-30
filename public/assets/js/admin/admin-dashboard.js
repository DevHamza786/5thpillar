(function () {
    document.querySelectorAll('.admin-dash, .admin-cms').forEach(function (root) {
        requestAnimationFrame(function () {
            root.classList.add('is-ready');
        });
    });

    var dash = document.querySelector('.admin-dash');
    if (!dash || window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
        return;
    }

    dash.querySelectorAll('.admin-dash__metric-value[data-count]').forEach(function (el) {
        var target = parseInt(el.getAttribute('data-count'), 10);
        if (isNaN(target) || target <= 0) {
            return;
        }

        var duration = 700;
        var start = performance.now();

        function tick(now) {
            var progress = Math.min((now - start) / duration, 1);
            var eased = 1 - Math.pow(1 - progress, 3);
            el.textContent = Math.round(target * eased).toLocaleString();
            if (progress < 1) {
                requestAnimationFrame(tick);
            }
        }

        el.textContent = '0';
        requestAnimationFrame(tick);
    });
})();
