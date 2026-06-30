/**
 * Copy URL buttons in admin CMS tables.
 */
document.addEventListener('click', function (event) {
    var button = event.target.closest('.cms-copy-url-btn');
    if (!button) {
        return;
    }

    var targetId = button.getAttribute('data-copy-target');
    var input = targetId ? document.getElementById(targetId) : null;
    var text = input ? input.value : (button.getAttribute('data-copy-text') || '');

    if (!text) {
        return;
    }

    var copiedLabel = button.getAttribute('data-copied-label') || 'Copied!';
    var originalLabel = button.getAttribute('data-original-label') || button.textContent;

    function showCopied() {
        button.textContent = copiedLabel;
        button.classList.add('is-copied');
        window.setTimeout(function () {
            button.textContent = originalLabel;
            button.classList.remove('is-copied');
        }, 1500);
    }

    if (navigator.clipboard && navigator.clipboard.writeText) {
        navigator.clipboard.writeText(text).then(showCopied).catch(function () {
            if (input) {
                input.select();
                document.execCommand('copy');
                showCopied();
            }
        });

        return;
    }

    if (input) {
        input.select();
        document.execCommand('copy');
        showCopied();
    }
});
