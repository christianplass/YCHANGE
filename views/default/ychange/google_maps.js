define(function(require) {
    var $ = require("jquery");
    var mapInputs = $('.ychange-geolocation');

    function setGeolocationValue(input, latLng) {
        input.val(latLng.lat() + ',' + latLng.lng());
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

            map = new google.maps.Map(document.getElementById(mapElement.attr('id')), {
                center: { lat: 48.6908333333, lng: 9.14055555556 },
                zoom: 4
            });
            map.addListener('click', function(event) {
                if ( timeout ) {
                    clearTimeout(timeout);
                    timeout = null;
                }

                timeout = setTimeout(function() {
                    if ( !marker ) {
                        marker = new google.maps.Marker({
                            position: event.latLng,
                            map: map,
                            draggable: true
                        });

                        marker.addListener('dragend', function() {
                            setGeolocationValue(_this, marker.getPosition());
                        });
                    } else {
                        marker.setPosition(event.latLng);
                    }

                    setGeolocationValue(_this, event.latLng);
                }, 250);
            });

            map.addListener('dblclick', function() {
                if ( timeout ) {
                    clearTimeout(timeout);
                    timeout = null;
                }
            });
        });
    }
});
