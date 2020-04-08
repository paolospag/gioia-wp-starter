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

  // Handle theme color scheme changes
  $.each(%DOMAIN_NAME%_color_scheme, function(i, name) {
    wp.customize(name, function(value) {
      value.bind(function(to, from) {
        var rootStyle = $('[id="%DOMAIN_NAME%-theme-inline-css"]').text();
        var strToReplace = '--'+name.replace(/_/g, '-')+': '+from;
        var strToAppend = '--'+name.replace(/_/g, '-')+': '+to;
        $('[id="%DOMAIN_NAME%-theme-inline-css"]').text( rootStyle.replace(strToReplace, strToAppend) );
      });
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
})(jQuery);
