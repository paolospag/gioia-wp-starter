<?php
/**
 * %THEME_NAME% Theme Customizer
 *
 * @package %DOMAIN_NAME%
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function %DOMAIN_NAME%_customize_register($wp_customize) {
  // Theme header name & color
  $wp_customize->get_setting('blogname')->transport = 'postMessage';
  $wp_customize->get_setting('header_textcolor')->transport = 'postMessage';
  if ( isset($wp_customize->selective_refresh) ) {
    $wp_customize->selective_refresh->add_partial('blogname', array(
      'selector'        => '.site-title a',
      'render_callback' => '%DOMAIN_NAME%_customize_partial_blogname',
    ));
  }

  // Theme color scheme
  $color_scheme = gwp_color_scheme();
  foreach($color_scheme as $name => $value) {
    $wp_customize->add_setting( $name, array(
      'default'   => $value['color'],
      'transport' => 'postMessage',
      'sanitize_callback' => 'sanitize_hex_color',
    ) );
    $wp_customize->add_control( new WP_Customize_Color_Control($wp_customize, $name, array(
      'section' => 'colors',
      'label'   => esc_html__($value['label'], '%DOMAIN_NAME%'),
    )) );
    if ( isset($wp_customize->selective_refresh) ) {
      $wp_customize->selective_refresh->add_partial($name, array(
        'selector'        => '[id="%DOMAIN_NAME%-theme-inline-css"]',
        'render_callback' => '%DOMAIN_NAME%_customize_partial_colors',
      ));
    }
  }

  // Theme social networks
  $social_networks = gwp_social_networks();
  $wp_customize->add_section( 'social_networks', array(
    'title'       => __('Social networks', '%DOMAIN_NAME%'),
    'description' => esc_html__('Imposta i social network del tuo sito.', '%DOMAIN_NAME%'),
    'priority'    => 90
  ) );
  foreach($social_networks as $name => $value) {
    $wp_customize->add_setting( $name, array(
      'default'   => $value['url'],
      'transport' => 'refresh',
      'sanitize_callback' => 'esc_url_raw'
    ) );
    $wp_customize->add_control( $name, array(
      'type'    => 'url',
      'section' => 'social_networks',
      'label'   => $value['label'],
    ) );
  }
}
add_action('customize_register', '%DOMAIN_NAME%_customize_register');

// Render the site title for the selective refresh partial.
function %DOMAIN_NAME%_customize_partial_blogname() {
  bloginfo('name');
}

// Render the color scheme for the selective refresh partial.
function %DOMAIN_NAME%_customize_partial_colors() {
  echo gwp_create_root_styles();
}

/**
 * Other partial renderds here.
 *
 * @link https://developer.wordpress.org/themes/customize-api/
 */

// Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
function %DOMAIN_NAME%_customize_preview_scripts() {
  wp_register_script( '%DOMAIN_NAME%-customizer', get_template_directory_uri() .'/assets/js/customizer.js', array('customize-preview'), date('Ymd'), true );
  wp_localize_script( '%DOMAIN_NAME%-customizer', '%DOMAIN_NAME%_color_scheme', array_keys(gwp_color_scheme()) );
  wp_enqueue_script( '%DOMAIN_NAME%-customizer' );
}
add_action('customize_preview_init', '%DOMAIN_NAME%_customize_preview_scripts');
