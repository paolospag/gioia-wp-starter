<?php
/**
 * Set up the WordPress core custom header feature.
 *
 * @link https://developer.wordpress.org/themes/functionality/custom-headers/
 * @uses %DOMAIN_NAME%_header_style()
 * @package %DOMAIN_NAME%
 */

function %DOMAIN_NAME%_custom_header_setup() {
  add_theme_support('custom-header', apply_filters('%DOMAIN_NAME%_custom_header_args', array(
    'default-image'          => '',
    'default-text-color'     => '000000',
    'width'                  => 1000,
    'height'                 => 250,
    'flex-height'            => true,
    'wp-head-callback'       => '%DOMAIN_NAME%_header_style',
  ) ));
}
add_action('after_setup_theme', '%DOMAIN_NAME%_custom_header_setup');

if ( !function_exists('%DOMAIN_NAME%_header_style') ):
  function %DOMAIN_NAME%_header_style() {
    $header_text_color = get_header_textcolor();
    // If no custom options for text are set, let's bail.
    if ( get_theme_support('custom-header', 'default-text-color') === $header_text_color ) {
      return;
    }
    
    // If we get this far, we have custom styles. Let's do this.
    ?>
    <style type="text/css">
    <?php
      if ( !display_header_text() ) {
        echo '.site-title, .site-description { position: absolute; clip: rect(1px, 1px, 1px, 1px); }';
      } else {
        echo '.site-title a, .site-description { color: #'. esc_attr($header_text_color) .' }';
      }
    ?>
    </style>
    <?php
  }
endif;
