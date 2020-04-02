<?php
/**
 * %THEME_NAME% Theme functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package %DOMAIN_NAME%
 */

/**
 * Load Utils before theme setup.
 */
include_once get_template_directory() .'/inc/utils.php';

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * @link https://developer.wordpress.org/reference/functions/add_theme_support/
 */
if ( !function_exists('%DOMAIN_NAME%_setup') ):
  function %DOMAIN_NAME%_setup() {
    // Let WordPress manage the document title to avoid hard-coded <title> tag.
    add_theme_support('title-tag');
    // Add default posts and comments RSS feed links to head.
    add_theme_support('automatic-feed-links');
    // Enable support for post thumbnails on posts and pages.
    add_theme_support('post-thumbnails');
    // Adding support for excerpt in page.
    add_post_type_support('page', 'excerpt');
    // Register theme nav menus.
    register_nav_menus( array(
      'main-menu' => esc_html__('Menu principale', '%DOMAIN_NAME%'),
    ) );
    // Switch default core markup to output valid HTML5.
    add_theme_support('html5', array(
  		'search-form',
  		'comment-form',
  		'comment-list',
  		'gallery',
  		'caption',
  	) );
    // Add theme support for selective refresh for widgets.
    add_theme_support('customize-selective-refresh-widgets');
    /**
     * Add support for core custom logo.
     *
     * @link https://codex.wordpress.org/Theme_Logo
     */
    add_theme_support('custom-logo', array(
      'height'      => 250,
      'width'       => 250,
      'flex-width'  => true,
      'flex-height' => true,
    ) );
    /**
     * Add and enable custom image sizes.
     * Uncomment or copy example below.
     *
     * @link https://developer.wordpress.org/reference/functions/add_image_size/
     * @example add_image_size('custom-size-name', 460, 520);
     */
    /**
     * Remove unnecessary WordPress core includes:
     * e.g. generator, feeds, emoji etc.
     *
     * Uncomment what are helpful to your case.
     */
    // remove_action('wp_head', 'wp_generator');
    // remove_action('wp_head', 'wlwmanifest_link');
    // remove_action('wp_head', 'rsd_link');
    // remove_action('wp_head', 'feed_links', 2);
    // remove_action('wp_head', 'rest_output_link_wp_head', 10);
    // remove_action('wp_head', 'wp_oembed_add_discovery_links', 10);
    // remove_action('wp_head', 'print_emoji_detection_script', 7);
    // remove_action('wp_print_styles', 'print_emoji_styles');
    // remove_action('admin_print_scripts', 'print_emoji_detection_script');
    // remove_action('admin_print_styles', 'print_emoji_styles');
    // remove_action('wp_head', 'rel_canonical');
    // remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0);
  }
endif;
add_action('after_setup_theme', '%DOMAIN_NAME%_setup');

/**
 * Add theme support for wp_body_open as
 * required from WordPress newer than 5.2.
 * Shim for WordPress older than 5.2.
 *
 * @link https://core.trac.wordpress.org/ticket/12563
 * @since 5.2.0
 */
if ( !function_exists('wp_body_open') ):
  function wp_body_open() {
    do_action('wp_body_open');
  }
endif;

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function %DOMAIN_NAME%_widgets_init() {
  register_sidebar( array(
    'name'          => esc_html__('Sidebar', '%DOMAIN_NAME%'),
    'id'            => 'sidebar',
    'description'   => esc_html__('Aggiungi widget qui.', '%DOMAIN_NAME%'),
    'before_widget' => '<div id="%1$s" class="widget %2$s">',
    'after_widget'  => '</div>',
    'before_title'  => '<h5 class="widget-title">',
    'after_title'   => '</h5>',
  ) );
  register_sidebar( array(
    'name'          => esc_html__('Footer #1', '%DOMAIN_NAME%'),
    'id'            => 'footer-1',
    'description'   => esc_html__('Aggiungi widget qui.', '%DOMAIN_NAME%'),
    'before_widget' => '<div id="%1$s" class="fwidget %2$s">',
    'after_widget'  => '</div>',
    'before_title'  => '<h6 class="fwidget-title">',
    'after_title'   => '</h6>',
  ) );
}
add_action('widgets_init', '%DOMAIN_NAME%_widgets_init');

/**
 * Enqueue scripts and styles.
 */
function %DOMAIN_NAME%_scripts() {
  global $is_IE;

  // Stylesheets
  wp_dequeue_style( 'wp-block-library' );
  wp_enqueue_style( 'google-fonts', '%GOOGLE_FONTS%', NULL, NULL, 'all' );
  wp_enqueue_style( 'bootstrap-base', get_template_directory_uri() .'/assets/css/bootstrap-base.min.css', NULL, '4.4.1', 'all' );
  wp_enqueue_style( '%DOMAIN_NAME%-theme', get_template_directory_uri() .'/assets/css/main.css', NULL, '1.0.0', 'all' );
  // https://developer.wordpress.org/reference/functions/wp_add_inline_style/#user-contributed-notes
  wp_add_inline_style( '%DOMAIN_NAME%-theme', gwp_create_root_styles() );
  if (is_admin_bar_showing()) {
    wp_add_inline_style( 'admin-bar', gwp_create_admin_styles() );
  }
  wp_enqueue_style( '%DOMAIN_NAME%-style', get_stylesheet_uri() );

  // Scripts
  wp_enqueue_script( 'jquery' );
  if ($is_IE) {
    wp_enqueue_script( 'object-fill-polyfill', get_template_directory_uri() .'/assets/js/object-fit.polyfill.js', array(), '2.3.4', true );
    wp_enqueue_script( 'css-vars-ponyfill', get_template_directory_uri() .'/assets/js/css-vars.ponyfill.js', array(), '2.1.2', true );
    wp_add_inline_script( 'css-vars-ponyfill', 'cssVars({});' );
  }
  wp_register_script( '%DOMAIN_NAME%-theme-scripts', get_template_directory_uri() .'/assets/js/main.js', array(), '1.0.0', true );
  wp_localize_script( '%DOMAIN_NAME%-theme-scripts', '%DOMAIN_NAME%_utils', array(
    'url' => esc_url(home_url()),
    'ajax_url' => esc_url(site_url().'/wp-admin/admin-ajax.php'),
    /**
     * Other JavaScript utils here...
     * @link https://rudrastyh.com/wordpress/load-more-posts-ajax.html
     */
	) );
  wp_enqueue_script( '%DOMAIN_NAME%-theme-scripts' );
  if ( is_singular() && comments_open() && get_option('thread_comments') ) {
    wp_enqueue_script( 'comment-reply' );
  }
}
add_action('wp_enqueue_scripts', '%DOMAIN_NAME%_scripts');

/**
 * Add custom classes to the array of body classes.
 *
 * @param array $classes
 * @return array
 */
function %DOMAIN_NAME%_body_classes($classes) {
  // Adds a class of hfeed to non-singular pages.
  if ( !is_singular() ) {
    $classes[] = 'hfeed';
  }
  // Adds a class when there is no sidebar present.
  if ( !is_active_sidebar('sidebar') ) {
    $classes[] = 'no-sidebar';
  }
  return $classes;
}
add_filter('body_class', '%DOMAIN_NAME%_body_classes');

// Add a pingback url auto-discovery header for single posts, pages, or attachments.
function %DOMAIN_NAME%_pingback_header() {
  if ( is_singular() && pings_open() ) {
    printf('<link rel="pingback" href="%s">', esc_url( get_bloginfo('pingback_url') ));
  }
}
add_action('wp_head', '%DOMAIN_NAME%_pingback_header');

// Handle default query targeting specific pages.
function %DOMAIN_NAME%_query_handler($query) {
  if ( !is_admin() && $query->is_main_query() ) {
    /**
     * Customizations here...
     *
     * @link https://developer.wordpress.org/reference/hooks/pre_get_posts/
     */
  }
}
add_action('pre_get_posts', '%DOMAIN_NAME%_query_handler');

/**
 * Handle WordPress excerpt:
 * - change the excerpt length
 * - change the excerpt more string
 */
function %DOMAIN_NAME%_custom_excerpt_length($length) {
  return 80;
}
add_filter('excerpt_length', '%DOMAIN_NAME%_custom_excerpt_length', 999);
function %DOMAIN_NAME%_custom_excerpt_more($more) {
  return '&hellip;';
}
add_filter('excerpt_more', '%DOMAIN_NAME%_custom_excerpt_more');

// Stop WordPress wrapping images in a <p> tag
function %DOMAIN_NAME%_remove_ptags_on_image($content) {
  return preg_replace('/<p>(\s*)(<img .* \/>)(\s*)<\/p>/iU', '\2', $content);
}
add_filter('the_content', '%DOMAIN_NAME%_remove_ptags_on_image');

/**
 * Contact Form 7 integrations.
 */
if ( class_exists('WPCF7') ) {
  // Stop wrap form control into <p> tag
  add_filter('wpcf7_autop_or_not', '__return_false');
}

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() .'/inc/custom-header.php';

/**
 * Implement customizer additions.
 */
require get_template_directory() .'/inc/customizer.php';

/**
 * Load custom post types and taxonomies.
 */
require get_template_directory() .'/inc/custom-types.php';
require get_template_directory() .'/inc/custom-taxonomies.php';

/**
 * Advanced Custom Fields integrations.
 */
if ( class_exists('ACF') ) {
  require get_template_directory() .'/inc/custom-acf.php';
}

/**
 * Load WooCommerce compatibility file.
 */
if ( class_exists('WooCommerce') ) {
  require get_template_directory() .'/inc/woocommerce.php';
}

/**
 * Manage WPML synchronizations and language switcher
 */
if ( class_exists('SitePress') ) {
  require get_template_directory() .'/inc/custom-wpml.php';
}
