/**
 * Urdu locale: auto-select Urdu in Google Translate widget (loaded from app layout).
 * Requires translate.google.com/element.js with cb=googleTranslateElementInit
 */
window.googleTranslateElementInit = function () {
    new google.translate.TranslateElement(
        {
            pageLanguage: 'en',
            includedLanguages: 'ur',
            layout: google.translate.TranslateElement.InlineLayout.SIMPLE,
            autoDisplay: false,
        },
        'google_translate_element'
    );

    setTimeout(function () {
        var select = document.querySelector('select.goog-te-combo');
        if (select) {
            select.value = 'ur';
            select.dispatchEvent(new Event('change'));
        }
    }, 1000);
};
