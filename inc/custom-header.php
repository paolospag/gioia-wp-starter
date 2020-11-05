<?php
if ( !defined('ABSPATH') ) {
  exit;
}

/**
 * Set up the WordPress core custom header feature.
 *
 * @link https://developer.wordpress.org/themes/functionality/custom-headers/
 * @uses %DOMAIN_NAME%_header_style()
 * @package %DOMAIN_NAME%
 */

function %DOMAIN_NAME%_custom_header_setup() {
  add_theme_support('custom-header', apply_filters('%DOMAIN_NAME%_custom_header_args', array(
    'default-image'          => get_template_directory_uri() .'/assets/img/header-placeholder.jpg',
    'default-text-color'     => 'e64e28',
    'width'                  => 1920,
    'height'                 => 720,
    'flex-height'            => true,
    'wp-head-callback'       => '%DOMAIN_NAME%_header_style',
  ) ));
}
add_action('after_setup_theme', '%DOMAIN_NAME%_custom_header_setup');

if ( !function_exists('%DOMAIN_NAME%_header_style') ):
  function %DOMAIN_NAME%_header_style() {
    $header_logo = get_theme_mod('custom_logo');
    $header_text_color = get_header_textcolor();
    ?>
    <style type="text/css">
    <?php
      /**
       * If no custom option for brand logo, let's style text.
       * Otherwise, if we get this far, we have custom styles.
       */
      if ( !$header_logo ) {
        if ( !display_header_text() )
          echo '.site-title { position: absolute; clip: rect(1px, 1px, 1px, 1px); }';
        else
          echo '.site-title, .site-title a { color: #'. esc_attr($header_text_color) .' }';
      } else {
        echo '.site-logo a, .site-logo a:hover, .site-logo a:focus { color: inherit; text-decoration: none; }';
      }
    ?>
    </style>
    <?php
  }
endif;
