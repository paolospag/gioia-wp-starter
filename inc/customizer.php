<?php
if ( !defined('ABSPATH') ) {
  exit;
}

/**
 * %THEME_NAME% Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 * @package %DOMAIN_NAME%
 */
function %DOMAIN_NAME%_customize_register($wp_customize) {
  // Theme sanitize utils
  function %DOMAIN_NAME%_sanitize_checkbox($input) {
    return ( (isset($input) && $input == true) ? true : false );
  }

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
    $option_name = $name.'_color';
    $display_label = 'Colore '.strtolower($value['label']);
    $wp_customize->add_setting( $option_name, array(
      'default'   => $value['color'],
      'transport' => 'postMessage',
      'sanitize_callback' => 'sanitize_hex_color',
    ) );
    $wp_customize->add_control( new WP_Customize_Color_Control($wp_customize, $option_name, array(
      'section' => 'colors',
      'label'   => esc_html__($display_label, '%DOMAIN_NAME%'),
    )) );
    if ( isset($wp_customize->selective_refresh) ) {
      $wp_customize->selective_refresh->add_partial($option_name, array(
        'selector'        => '[id="%DOMAIN_NAME%-theme-inline-css"]',
        'render_callback' => '%DOMAIN_NAME%_customize_partial_colors',
      ));
    }
  }

  // Theme additional settings
  $wp_customize->add_section( 'additional_settings', array(
    'title'       => __('Impostazioni aggiuntive', '%DOMAIN_NAME%'),
    'description' => esc_html__('Imposta qui alcune opzioni aggiuntive per il sito.', '%DOMAIN_NAME%'),
    'priority'    => 85
  ) );
  $wp_customize->add_setting( 'show_search_button', array(
    'default'   => '',
    'sanitize_callback' => '%DOMAIN_NAME%_sanitize_checkbox'
  ) );
  $wp_customize->add_control( 'show_search_button', array(
    'type'    => 'checkbox',
    'section' => 'additional_settings',
    'label'   => __('Mostra ricerca', '%DOMAIN_NAME%'),
    'description' => __('Scegli se mostrare o meno il pulsante di ricerca nel menu principale.', '%DOMAIN_NAME%')
  ) );
  if ( class_exists('WooCommerce') ) {
    if ( function_exists('%DOMAIN_NAME%_woocommerce_cart_button') ) {
      $wp_customize->add_setting( 'show_cart_button', array(
        'default'   => '',
        'sanitize_callback' => '%DOMAIN_NAME%_sanitize_checkbox'
      ) );
      $wp_customize->add_control( 'show_cart_button', array(
        'type'    => 'checkbox',
        'section' => 'additional_settings',
        'label'   => __('Mostra carrello', '%DOMAIN_NAME%'),
        'description' => __('Scegli se mostrare o meno il pulsante del carrello nel menu principale.', '%DOMAIN_NAME%')
      ) );
    }
    if ( function_exists('%DOMAIN_NAME%_woocommerce_loop_columns') ) {
      $wp_customize->add_setting( 'woo_loop_columns', array(
        'default'   => '',
        'sanitize_callback' => 'absint'
      ) );
      $wp_customize->add_control( 'woo_loop_columns', array(
        'type'    => 'number',
        'section' => 'additional_settings',
        'label'   => __('Prodotti per riga', '%DOMAIN_NAME%'),
        'description' => __("Scegli quanti prodotti mostrare per riga, nelle pagine dell'e-commerce.", '%DOMAIN_NAME%'),
        'input_attrs' => array(
          'min' => 1,
          'max' => 6,
          'step' => 1
        )
      ) );
    }
  }
  $wp_customize->add_setting( 'gtag_ua_code', array(
    'default'   => '',
    'transport' => 'refresh',
    'sanitize_callback' => 'wp_filter_nohtml_kses'
  ) );
  $wp_customize->add_control( 'gtag_ua_code', array(
    'type'    => 'text',
    'section' => 'additional_settings',
    'label'   => __('Codice UA Analytics', '%DOMAIN_NAME%'),
    'description' => __("Il codice UA (o ID monitoraggio) è una stringa dall'aspetto simile al seguente: UA-000000-2.", '%DOMAIN_NAME%'),
    'input_attrs' => array(
      'maxlength' => 20
    )
  ) );
  $wp_customize->add_setting( 'gmap_api_key', array(
    'default'   => '',
    'transport' => 'refresh',
    'sanitize_callback' => 'wp_filter_nohtml_kses'
  ) );
  $wp_customize->add_control( 'gmap_api_key', array(
    'type'    => 'text',
    'section' => 'additional_settings',
    'label'   => __('Google Maps API Key', '%DOMAIN_NAME%'),
    'description' => __("L'API Key è una chiave utilizzata per autenticare le richieste associate a questo sito e utili al corretto funzionamento delle mappe.", '%DOMAIN_NAME%'),
    'input_attrs' => array(
      'maxlength' => 50
    )
  ) );

  // Theme footer copyright
  $wp_customize->add_section( 'footer_copyright', array(
    'title'       => __('Copyright', '%DOMAIN_NAME%'),
    'description' => esc_html__('Imposta qui il copyright del sito.', '%DOMAIN_NAME%'),
    'priority'    => 90
  ) );
  $wp_customize->add_setting( 'copyright_text', array(
    'default'   => '',
    'transport' => 'postMessage',
    'sanitize_callback' => 'wp_kses_post'
  ) );
  $wp_customize->add_control( 'copyright_text', array(
    'type'    => 'textarea',
    'section' => 'footer_copyright',
    'label'   => __('Testo copyright', '%DOMAIN_NAME%'),
  ) );
  if ( isset($wp_customize->selective_refresh) ) {
    $wp_customize->selective_refresh->add_partial('copyright_text', array(
      'selector'        => '.footer__bottom p',
      'render_callback' => '%DOMAIN_NAME%_customize_partial_copyright',
    ));
  }

  // Theme social networks
  $social_networks = gwp_social_networks();
  $wp_customize->add_section( 'social_networks', array(
    'title'       => __('Social networks', '%DOMAIN_NAME%'),
    'description' => esc_html__('Imposta i social network del tuo sito.', '%DOMAIN_NAME%'),
    'priority'    => 95
  ) );
  foreach($social_networks as $name => $label) {
    $wp_customize->add_setting( $name, array(
      'default'   => '',
      'transport' => 'refresh',
      'sanitize_callback' => 'esc_url_raw'
    ) );
    $wp_customize->add_control( $name, array(
      'type'    => 'url',
      'section' => 'social_networks',
      'label'   => $label,
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

// Render the footer copyright for the selective refresh partial.
function %DOMAIN_NAME%_customize_partial_copyright() {
  $copyright_text = get_theme_mod('copyright_text', '&copy; GWP Theme');
  echo nl2br($copyright_text);
}

/**
 * Other partial renders here.
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
