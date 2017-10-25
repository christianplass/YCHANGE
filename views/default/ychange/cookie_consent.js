define(function(require) {
    var $ = require("jquery");

    var cookieConsentKey = 'cookie:consent';
    var cookieConsentElement = $('#ychange-cookie-consent');
    var hasGivenConsent = false;

    if ( window.localStorage && window.localStorage.getItem(cookieConsentKey) === 'true' ) {
        hasGivenConsent = true;
    }

    if ( !hasGivenConsent ) {
        cookieConsentElement.slideDown();
        cookieConsentElement.find('input[type="button"][name="agree"]').on('click', function() {
            if ( window.localStorage ) {
                try {
                    window.localStorage.setItem(cookieConsentKey, 'true');
                    cookieConsentElement.slideUp();
                } catch (err) {
                    // Handles the QuotaExceededError in some versions of Safari on iOS
                }
            }
        });
    }
});
