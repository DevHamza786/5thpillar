document.querySelectorAll('.notice.is-dismissible .notice-dismiss').forEach(function (btn) {
    btn.addEventListener('click', function () {
        var n = btn.closest('.notice');
        if (n) {
            n.remove();
        }
    });
});
