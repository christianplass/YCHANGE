define(function(require) {
    var $ = require('jquery');
    var elgg = require('elgg');
    var lightbox = require("elgg/lightbox");

    function isEmbeddableVideo(url) {
        var parsedUrl = elgg.parse_url(url);

        if ( parsedUrl.host === 'www.youtube.com' || parsedUrl.host === 'youtube.com' || parsedUrl.host === 'www.youtu.be' || parsedUrl.host === 'youtu.be' ) {
            return true;
        }

        return false;
    }

    function replaceEmbeddableVideoUrl(element) {
        var parsedUrl = elgg.parse_url(element.attr('href'));

        if ( ( parsedUrl.host === 'www.youtube.com' || parsedUrl.host === 'youtube.com' ) && parsedUrl.path === '/watch' && parsedUrl.query ) {
            element.attr('data-original-href', element.attr('href'));
            element.attr('href', parsedUrl.scheme + '://' + parsedUrl.host + '/embed/' + parsedUrl.query.substr(2));

            return true;
        } else if ( parsedUrl.host === 'www.youtu.be' || parsedUrl.host === 'youtu.be' ) {
            element.attr('data-original-href', element.attr('href'));
            element.attr('href', parsedUrl.scheme + '://www.youtube.com/embed' + parsedUrl.path);

            return true;
        }

        return false;
    }

    function addIcon(element, iconClass) {
        $('<i>')
            .addClass('fa')
            .addClass(iconClass)
            .attr('aria-hidden', 'true')
            .prependTo(element);
    }

    $('.elgg-page-body .elgg-output a').each(function(index, node) {
        var element = $(node);

        if ( isEmbeddableVideo( element.attr('href') ) ) {
            element.addClass('ychange-video-tutorial');
            replaceEmbeddableVideoUrl(element);
            addIcon(element, 'fa-youtube');
        } else {
            addIcon(element, 'fa-info-circle');
        }
    });

    lightbox.bind('a.ychange-video-tutorial', {
        iframe: true,
        width: $(window).width() - 30,
        height: $(window).height() - 30
    }, false);
});
