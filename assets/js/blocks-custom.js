(function($) {
  function initializeMap($block) {
    $block = !$block.hasClass('map')
      ? $block.find('.map > .map__container')
      : $block.find('.map__container');

    // Create map
    var $markers = $block.find('.map__marker');
    var options = $block.data('options');
    if (!options) options = {zoom: 14};
    var mapArgs = {
      zoom: options.zoom,
      // styles: options.style,
      mapTypeControl: false,
      streetViewControl: false,
      mapTypeId: google.maps.MapTypeId.ROADMAP
    };
    var map = new google.maps.Map($block[0], mapArgs);
    map.markers = [];
    $markers.each(function() {
      var mrk = $(this);
      var lat = mrk.data('lat');
      var lng = mrk.data('lng');
      var latLng = {
        lat: parseFloat(lat),
        lng: parseFloat(lng)
      };
      var marker = new google.maps.Marker({
        // icon: options.icon,
        position: latLng,
        map: map
      });
      map.markers.push(marker);
      if (mrk.html()) {
        var infowindow = new google.maps.InfoWindow({
          content: mrk.html()
        });
        google.maps.event.addListener(marker, 'click', function() {
          infowindow.open(map, marker);
        });
      }
    });

    // Center map
    var bounds = new google.maps.LatLngBounds();
    map.markers.forEach(function( marker ){
      bounds.extend({
        lat: marker.position.lat(),
        lng: marker.position.lng()
      });
    });
    if (map.markers.length == 1) {
      map.setCenter(bounds.getCenter());
    } else {
      map.fitBounds(bounds);
    }

    return map;
  }

  /**
   * Other block handlers here.
   */

  // Initialize each block on page load (front-end).
  $(document).ready(function() {
    if ( $('.map').length ) {
      $('.map').each(function() {
        initializeMap( $(this) );
      });
    }
  });

  // Initialize dynamic block preview (editor).
  if (window.acf) {
    window.acf.addAction('render_block_preview/type=map', initializeMap);
  }
})(jQuery);
