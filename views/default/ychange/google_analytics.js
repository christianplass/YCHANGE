define(function(require) {
    var elgg = require("elgg");

    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', elgg.data.ychange.analytics.key);
});
