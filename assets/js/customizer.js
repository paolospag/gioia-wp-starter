/**
 * Theme Customizer enhancements for a better user experience.
 *
 * Contains handlers to make Theme Customizer preview reload changes asynchronously.
 */

(function($) {
  // Handle site title changes
  wp.customize('blogname', function(value) {
    value.bind(function(to) {
      $('.site-title a').text(to);
    });
  });

  // Handle header text color changes
  wp.customize('header_textcolor', function(value) {
    value.bind(function(to) {
      if ('blank' === to) {
        $('.site-title').css({
          'clip': 'rect(1px, 1px, 1px, 1px)',
          'position': 'absolute'
        });
      } else {
        $('.site-title').css({
          'clip': 'auto',
          'position': 'relative'
        });
        $('.site-title, .site-title a').css({
          'color': to
        });
      }
    });
  });

  // Handle theme color scheme changes
  $.each(%DOMAIN_NAME%_color_scheme, function(i, name) {
    var option = name+'_color';
    var prop = '--'+option.replace(/_/g, '-');
    wp.customize(option, function(value) {
      value.bind(function(to) {
        document.documentElement.style.setProperty(prop, to);
      });
    });
  });

  // Handle theme footer copyright changes
  wp.customize('copyright_text', function(value) {
    value.bind(function(to) {
      $('.footer__bottom p').html(to);
    });
  });
})(jQuery);
