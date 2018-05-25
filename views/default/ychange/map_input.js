define(function(require) {
    var $ = require("jquery");
    var L = require("leafletjs");
    var mapInputs = $('.ychange-geolocation');

    function setGeolocationValue(input, latLng) {
        input.val(latLng.lat + ',' + latLng.lng);
    }

    if ( mapInputs.length > 0 ) {
        mapInputs.each(function() {
            // TODO Read value from input and initialize marker
            // Listen to changes within the input field and update the map accordingly
            var _this = $(this),
                mapElement = $('<div></div>'),
                map = null,
                marker = null,
                timeout = null;

            mapElement
                .attr('id', _this.attr('id') + '_map')
                .attr('class', 'ychange-map-input');
            _this.before(mapElement);

            map = L.map(mapElement.attr('id')).setView([48.6908333333, 9.14055555556], 4);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, <a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>',
                maxZoom: 18,
            }).addTo(map);

            map.on('click', function(e) {
                if ( timeout ) {
                    clearTimeout(timeout);
                    timeout = null;
                }

                timeout = setTimeout(function() {
                    if ( !marker ) {
                        marker = L.marker(e.latlng, {
                            draggable: true
                        }).addTo(map);

                        marker.on('dragend', function(e) {
                            setGeolocationValue(_this, marker.getLatLng());
                        });
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
