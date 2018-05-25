define(function(require) {
    var $ = require("jquery");
    var L = require("leafletjs");
    var mapInputs = $('.ychange-geolocation');

    function parseLocation(string) {
        var split = string.split(',');

        if ( split.length === 2 ) {
            var lat = parseFloat(split[0]);
            var lng = parseFloat(split[1]);

            if ( !( isNaN(lat) && isNaN(lng) ) ) {
                return [lat, lng];
            }
        }

        return null;
    }

    function setGeolocationValue(input, latLng) {
        input.val(latLng.lat + ',' + latLng.lng);
    }

    function initMarker(input, map, latLng) {
        var marker = L.marker(latLng, {
            draggable: true
        }).addTo(map);

        marker.on('dragend', function(e) {
            setGeolocationValue(input, marker.getLatLng());
        });

        return marker;
    }

    if ( mapInputs.length > 0 ) {
        mapInputs.each(function() {
            // TODO Listen to changes within the input field and update the map accordingly
            var _this = $(this),
                mapElement = $('<div></div>'),
                map = null,
                marker = null,
                timeout = null,
                location = null;

            location = parseLocation(_this.val());

            mapElement
                .attr('id', _this.attr('id') + '_map')
                .attr('class', 'ychange-map-input');
            _this.before(mapElement);

            map = L.map(mapElement.attr('id')).setView([48.6908333333, 9.14055555556], 4);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, <a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>',
                maxZoom: 18,
            }).addTo(map);

            if ( location ) {
                marker = initMarker(_this, map, location);
            }

            map.on('click', function(e) {
                if ( timeout ) {
                    clearTimeout(timeout);
                    timeout = null;
                }

                timeout = setTimeout(function() {
                    if ( !marker ) {
                        marker = initMarker(_this, map, e.latlng);
                    } else {
                        marker.setLatLng(e.latlng);
                    }

                    setGeolocationValue(_this, e.latlng);
                }, 250);
            });

            map.on('dblclick', function(e) {
                if ( timeout ) {
                    clearTimeout(timeout);
                    timeout = null;
                }
            });
        });
    }
});
