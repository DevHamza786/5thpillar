(function () {
    var toggle = document.getElementById('admin-shell-toggle');
    var menu = document.getElementById('adminmenumain');
    var backdrop = document.getElementById('admin-shell-backdrop');

    if (!toggle || !menu) {
        return;
    }

    if (!backdrop) {
        backdrop = document.createElement('div');
        backdrop.id = 'admin-shell-backdrop';
        backdrop.className = 'admin-shell-backdrop';
        backdrop.setAttribute('aria-hidden', 'true');
        document.body.appendChild(backdrop);
    }

    function setOpen(open) {
        document.body.classList.toggle('admin-shell-menu-open', open);
        toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
        backdrop.setAttribute('aria-hidden', open ? 'false' : 'true');
    }

    toggle.addEventListener('click', function () {
        setOpen(!document.body.classList.contains('admin-shell-menu-open'));
    });

    backdrop.addEventListener('click', function () {
        setOpen(false);
    });

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') {
            setOpen(false);
        }
    });

    window.addEventListener('resize', function () {
        if (window.innerWidth > 960) {
            setOpen(false);
        }
    });

    menu.querySelectorAll('a.menu-top').forEach(function (link) {
        link.addEventListener('click', function () {
            if (window.innerWidth <= 960) {
                setOpen(false);
            }
        });
    });
})();
