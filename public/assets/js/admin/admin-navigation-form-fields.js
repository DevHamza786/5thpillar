(function () {
    var sel = document.getElementById('link_type');
    if (!sel) {
        return;
    }
    function sync() {
        var v = sel.value;
        document.querySelectorAll('.field-link').forEach(function (el) {
            el.style.display = 'none';
        });
        if (v === 'page_slug') {
            document.getElementById('wrap-page_slug').style.display = '';
        }
        if (v === 'named_route') {
            document.getElementById('wrap-route_name').style.display = '';
        }
        if (v === 'custom_url') {
            document.getElementById('wrap-custom_url').style.display = '';
        }
        if (v === 'media') {
            document.getElementById('wrap-cms_media_id').style.display = '';
        }
    }
    sel.addEventListener('change', sync);
    sync();
})();
