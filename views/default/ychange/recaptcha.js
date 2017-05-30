define(function(require) {
    var $ = require("jquery");
    var form = $('.g-recaptcha').parents('form.elgg-form');

    window.reCaptchaSolvedCb = function(token) {
        form.off('submit').trigger('submit');
    };

    form.on('submit', function(e) {
        e.preventDefault();
        grecaptcha.execute();
    });
});
