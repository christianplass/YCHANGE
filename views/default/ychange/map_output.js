define(function(require) {
    var $ = require("jquery");
    var L = require("leafletjs");
    var mapOutputs = $('.ychange-geolocation');

    if ( mapOutputs.length > 0 ) {
        mapOutputs.each(function() {
            var _this = $(this);
            var locationStr = _this.data('location');
            if ( !locationStr ) {
                return;
            }

            var location = locationStr.split(',');
            if ( location.length !== 2 ) {
                return;
            }

            var mapElement = $('<div></div>'),
                map = null,
                marker = null;

            mapElement
                .attr('id', _this.attr('id') + '-map')
                .attr('class', 'ychange-map-output');
            _this.append(mapElement);

            map = L.map(mapElement.attr('id')).setView(location, 8);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, <a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>',
                maxZoom: 18,
            }).addTo(map);

            marker = L.marker(location, {
                draggable: false
            }).addTo(map);
        });
    }
});
