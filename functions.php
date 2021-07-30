<?php
/**
 * %THEME_NAME% Theme functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package %DOMAIN_NAME%
 */

define('GWP_BLOCK_EDITOR', %ENABLE_GUTENBERG%);
define('GWP_LOCALE', get_locale());
if ( !defined('GWP_THEME_VERSION') ) {
  $theme = wp_get_theme();
  define('GWP_THEME_VERSION', $theme->get('Version'));
}

/**
 * Load utilities before theme setup.
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

    /**
     * Make this Theme available for translation.
     * @package %DOMAIN_NAME%
     */
    load_theme_textdomain('%DOMAIN_NAME%', get_template_directory() .'/languages');

    if ( GWP_BLOCK_EDITOR ) {
      // Add Block Editor wide alignment support.
      add_theme_support('align-wide');
      // Enable Block Editor front-end styles.
      add_theme_support('wp-block-styles');
      // Enable Block Editor embeds support.
      add_theme_support('responsive-embeds');
      // Add theme font-sizes for Block Editor.
      add_theme_support(
        'editor-font-sizes',
        gwp_create_block_font_sizes()
      );
      // Add theme color palette for Block Editor.
      add_theme_support(
        'editor-color-palette',
        gwp_create_block_color_palettes()
      );
      // Remove support for Block Editor patterns.
      remove_theme_support('core-block-patterns');
    }

    // Register theme nav menus.
    register_nav_menus( array(
      'primary' => esc_html__('Menu principale', '%DOMAIN_NAME%'),
    ) );

    // Switch default core markup to output valid HTML5.
    add_theme_support('html5', array(
      'search-form',
      'comment-form',
      'comment-list',
      'gallery',
      'caption',
      'script',
      'navigation-widgets',
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
     *
     * @link https://developer.wordpress.org/reference/functions/add_image_size/
     */
    add_image_size('blog-thumbnail', 550, 550);

    // Remove WordPress links included in <head>
    remove_action('wp_head', 'wp_generator');
    remove_action('wp_head', 'wlwmanifest_link');
    // remove_action('wp_head', 'rsd_link');
    // remove_action('wp_head', 'feed_links', 2);
    remove_action('wp_head', 'rest_output_link_wp_head', 10);
    remove_action('wp_head', 'wp_oembed_add_discovery_links', 10);

    // Remove WordPress emoji
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_action('admin_print_scripts', 'print_emoji_detection_script');
    remove_action('admin_print_styles', 'print_emoji_styles');

    // Remove default WordPress Canonical & Shortlink
    remove_action('wp_head', 'rel_canonical');
    remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0);
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
    'name'          => esc_html__('Footer', '%DOMAIN_NAME%'),
    'id'            => 'footer',
    'description'   => esc_html__('Aggiungi widget qui.', '%DOMAIN_NAME%'),
    'before_widget' => '<div id="%1$s" class="fwidget %2$s">',
    'after_widget'  => '</div>',
    'before_title'  => '<h6 class="fwidget-title">',
    'after_title'   => '</h6>',
  ) );
}
add_action('widgets_init', '%DOMAIN_NAME%_widgets_init');

/**
 * Enqueue scripts & styles.
 */
function %DOMAIN_NAME%_scripts() {

  // Stylesheets
  if ( !GWP_BLOCK_EDITOR ) {
    wp_dequeue_style( 'wp-block-library' );
  }
  wp_enqueue_style( 'google-fonts', '%GOOGLE_FONTS%', null, null, 'all' );
  wp_enqueue_style( 'bootstrap-base', get_template_directory_uri() .'/assets/css/bootstrap-base.min.css', [], '4.6.0', 'all' );
  if ( GWP_BLOCK_EDITOR ) {
    wp_register_style( '%DOMAIN_NAME%-blocks', get_template_directory_uri() .'/assets/css/blocks-custom.css', [], GWP_THEME_VERSION );
    wp_enqueue_style( '%DOMAIN_NAME%-theme', get_template_directory_uri() .'/assets/css/main.css', ['%DOMAIN_NAME%-blocks'], GWP_THEME_VERSION, 'all' );
  } else {
    wp_enqueue_style( '%DOMAIN_NAME%-theme', get_template_directory_uri() .'/assets/css/main.css', [], GWP_THEME_VERSION, 'all' );
  }
  wp_add_inline_style( '%DOMAIN_NAME%-theme', gwp_create_root_styles() );
  wp_add_inline_style( '%DOMAIN_NAME%-theme', gwp_create_base_styles() );

  // Scripts
  wp_enqueue_script( 'jquery' );
  wp_enqueue_script( 'object-fit-polyfill', get_template_directory_uri() .'/assets/js/object-fit.polyfill.js', [], '3.2.4', true );
  wp_enqueue_script( 'css-vars-ponyfill', get_template_directory_uri() .'/assets/js/css-vars.ponyfill.js', [], '2.4.0', true );
  if ( class_exists('ACF') && $google_map_api_key = acf_get_setting('google_api_key') ) {
    wp_enqueue_script( 'google-maps', "https://maps.googleapis.com/maps/api/js?key=$google_map_api_key", [], '3', true );
  }
  if ( GWP_BLOCK_EDITOR ) {
    wp_register_script( '%DOMAIN_NAME%-blocks', get_template_directory_uri() .'/assets/js/blocks-custom.js', [], GWP_THEME_VERSION, true );
    wp_enqueue_script( '%DOMAIN_NAME%-theme', get_template_directory_uri() .'/assets/js/main.js', ['%DOMAIN_NAME%-blocks'], GWP_THEME_VERSION, true );
  } else {
    wp_enqueue_script( '%DOMAIN_NAME%-theme', get_template_directory_uri() .'/assets/js/main.js', [], GWP_THEME_VERSION, true );
  }
  wp_localize_script( '%DOMAIN_NAME%-theme', '%DOMAIN_NAME%_utils', array(
    'url' => esc_url( site_url('/') ),
    'ajax_url' => esc_url( site_url('/wp-admin/admin-ajax.php') ),
    'loading' => esc_attr__('Caricamento&hellip;', '%DOMAIN_NAME%'),
    'language' => esc_attr( explode('_', GWP_LOCALE)[0] ),
    'prev_icon' => gwp_svg_icon('arrow-left'),
    'next_icon' => gwp_svg_icon('arrow-right')
	) );
  if ( is_singular() && comments_open() && get_option('thread_comments') ) {
    wp_enqueue_script( 'comment-reply' );
  }
}
add_action('wp_enqueue_scripts', '%DOMAIN_NAME%_scripts');

/**
 * Enqueue Block Editor assets.
 *
 * @link https://developer.wordpress.org/reference/hooks/enqueue_block_assets/
 * @var GWP_BLOCK_EDITOR
 */
if ( GWP_BLOCK_EDITOR ) {
  function %DOMAIN_NAME%_block_editor_assets() {
    // Stylesheets
    wp_enqueue_style( 'bootstrap-grid', get_template_directory_uri() .'/assets/css/bootstrap-grid.min.css', [], '4.6.0' );
    wp_enqueue_style( '%DOMAIN_NAME%-editor', get_template_directory_uri() .'/assets/css/blocks-editor.css', ['wp-edit-blocks'], time() );
    wp_enqueue_style( '%DOMAIN_NAME%-blocks', get_template_directory_uri() .'/assets/css/blocks-custom.css', ['wp-edit-blocks'], time() );
    wp_add_inline_style( '%DOMAIN_NAME%-editor', gwp_create_root_styles() );

    // Scripts
    if ( class_exists('ACF') && $google_map_api_key = acf_get_setting('google_api_key') ) {
      wp_enqueue_script( 'google-maps', "https://maps.googleapis.com/maps/api/js?key=$google_map_api_key", [], '3', true );
    }
    wp_enqueue_script( '%DOMAIN_NAME%-editor', get_template_directory_uri() .'/assets/js/blocks-editor.js', ['wp-blocks', 'wp-dom-ready', 'wp-edit-post'], time() );

    wp_enqueue_script( '%DOMAIN_NAME%-blocks', get_template_directory_uri() .'/assets/js/blocks-custom.js', [], time() );
  }
  add_action('enqueue_block_editor_assets', '%DOMAIN_NAME%_block_editor_assets', 20);
}

/**
 * Disable Block Editor (formerly Gutenberg) without plugin:
 * we need to disable it at all, for specific post or type(s).
 *
 * @param  boolean $enabled [default usage value].
 * @param  WP_Post $post [current post instance].
 * @return boolean $enabled [modified usage value].
 */
function %DOMAIN_NAME%_disable_block_editor_by_post($enabled, $post) {
  if ( !GWP_BLOCK_EDITOR ) return false;

  if ( in_array($post->post_type, ['product']) ) {
    $enabled = false;
  }
  return $enabled;
}
add_filter('use_block_editor_for_post', '%DOMAIN_NAME%_disable_block_editor_by_post', 10, 2);

/**
 * Add the custom image size(s) to Block Editor select.
 *
 * @param  array $sizes [default choices].
 * @return array [merged choices].
 */
if ( GWP_BLOCK_EDITOR ) {
  function %DOMAIN_NAME%_image_size_choices($sizes) {
    return array_merge($sizes, [
      'blog-thumbnail' => _x('Blog Thumbnail', 'Block Editor', '%DOMAIN_NAME%'),
    ]);
  }
  add_filter('image_size_names_choose', '%DOMAIN_NAME%_image_size_choices', 20, 1);
}

/**
 * Add theme color meta tag.
 *
 * @return void
 */
function %DOMAIN_NAME%_theme_color_meta() {
  $default_color = gwp_color_scheme()['primary']['color'];
  $theme_color = get_theme_mod('primary_color', $default_color);
  echo '<meta name="theme-color" content="'. esc_attr($theme_color) .'" />';
}
add_action('wp_head', '%DOMAIN_NAME%_theme_color_meta', 20);

/**
 * Add theme favicon link,
 * to support some older browsers.
 *
 * @return void
 */
function %DOMAIN_NAME%_favicon_link() {
  $default_icon = get_site_icon_url();
  $favicon_path = ABSPATH.'/favicon.ico';
  if ( !empty($default_icon) && file_exists($favicon_path) ) {
    echo '<link rel="shortcut icon" href="'.site_url('/favicon.ico').'" />';
  }
}
add_action('wp_head', '%DOMAIN_NAME%_favicon_link', 100);

/**
 * Add a pingback URL auto-discovery header for single posts, pages, or attachments.
 *
 * @return void
 */
function %DOMAIN_NAME%_pingback_header() {
  if ( is_singular() && pings_open() ) {
    printf('<link rel="pingback" href="%s">', esc_url( get_bloginfo('pingback_url') ));
  }
}
add_action('wp_head', '%DOMAIN_NAME%_pingback_header');

/**
 * Add Google Analytics script if UA code is available:
 * we want to make sure there are no conflicts with the Site Kit plugin.
 *
 * @return void
 */
function %DOMAIN_NAME%_google_analytics() {
  if ( !class_exists('Google\Site_Kit\Plugin') && wp_get_environment_type() === 'production' && ($ganalytics_code = get_theme_mod('ganalytics_code', false)) ) {
    print("<!-- Global site tag (gtag.js) - Google Analytics -->\n\t<script async src=\"https://www.googletagmanager.com/gtag/js?id=$ganalytics_code\"></script>\n\t<script>window.dataLayer = window.dataLayer || []; function gtag(){dataLayer.push(arguments);} gtag('js', new Date()); gtag('config', '$ganalytics_code');</script>");
  }
}
add_action('wp_head', '%DOMAIN_NAME%_google_analytics', 0);

/**
 * Move WordPress admin bar from top to bottom.
 *
 * @return void
 */
function %DOMAIN_NAME%_admin_bar() {
  remove_action('wp_head', '_admin_bar_bump_cb');
  if ( is_admin_bar_showing() ) {
    wp_add_inline_style( 'admin-bar', gwp_create_admin_styles() );
  }
}
add_action('get_header', '%DOMAIN_NAME%_admin_bar', 10);

/**
 * Add custom classes to the array of body classes.
 *
 * @param array $classes
 * @return array
 */
function %DOMAIN_NAME%_body_classes($classes) {
  // Add a class of hfeed to non-singular pages.
  if ( !is_singular() ) {
    $classes[] = 'hfeed';
  }
  // Add a class when there is no sidebar present.
  if ( !is_active_sidebar('sidebar') ) {
    $classes[] = 'no-sidebar';
  }
  return $classes;
}
add_filter('body_class', '%DOMAIN_NAME%_body_classes');

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

// Stop WordPress wrapping images in a <p> tag.
function %DOMAIN_NAME%_remove_ptags_on_image($content) {
  return preg_replace('/<p>(\s*)(<img .* \/>)(\s*)<\/p>/iU', '\2', $content);
}
add_filter('the_content', '%DOMAIN_NAME%_remove_ptags_on_image');
add_filter('widget_text_content', '%DOMAIN_NAME%_remove_ptags_on_image');

/**
 * Handle WordPress Admin login screen.
 *
 * @link https://codex.wordpress.org/Customizing_the_Login_Form
 */
function %DOMAIN_NAME%_login_logo_link() {
  return home_url();
}
add_filter('login_headerurl', '%DOMAIN_NAME%_login_logo_link', 110, 0);
function %DOMAIN_NAME%_login_logo_title() {
  return get_bloginfo('title');
}
add_filter('login_headertext', '%DOMAIN_NAME%_login_logo_title', 110, 0);
function %DOMAIN_NAME%_login_form_style() {
  $site_logo_id = get_theme_mod('custom_logo', false);
  wp_add_inline_style( 'login', gwp_create_login_styles($site_logo_id) );
}
add_action('login_enqueue_scripts', '%DOMAIN_NAME%_login_form_style', 110);

/**
 * Disable XML-RPC by default to avoid brute force attacks.
 *
 * @return void
 */
add_filter('xmlrpc_enabled', '__return_false');
remove_action('xmlrpc_rsd_apis', 'rest_output_rsd');
remove_action('template_redirect', 'rest_output_link_header', 11);

/**
 * Contact Form 7 integrations.
 */
if ( class_exists('WPCF7') ) {
  // Stop wrap form control into <p> tag.
  add_filter('wpcf7_autop_or_not', '__return_false');
  // Create dynamic dataset for country field.
  function %DOMAIN_NAME%_cf7_countries_data($n, $options, $args) {
    if ( in_array('countries', $options) && function_exists('gwp_countries') ) {
      $countries = %DOMAIN_NAME%_countries();
      return $countries;
    }
    return null;
  }
  add_filter('wpcf7_form_tag_data_option', '%DOMAIN_NAME%_cf7_countries_data', 10, 3);
}

/**
 * Yoast SEO integrations.
 */
if ( class_exists('WPSEO_Options') ) {
  /**
   * Create Theme-based breadcrumb with Yoast.
   * @method %DOMAIN_NAME%_theme_breadcrumb
   */
  if ( !function_exists('%DOMAIN_NAME%_theme_breadcrumb') ) {
    function %DOMAIN_NAME%_theme_breadcrumb() {
      yoast_breadcrumb('<ol class="breadcrumb"><li class="breadcrumb__item">', '</li></ol>');
    }
    function %DOMAIN_NAME%_yoast_breadcrumb_items() {
      $yoast_options = get_option('wpseo_titles', []);
      $separator = isset($yoast_options['breadcrumbs-sep']) ? $yoast_options['breadcrumbs-sep'] : '&nbsp;';
      return "</li>&nbsp;$separator<li class=\"breadcrumb__item\">";
    }
    add_filter('wpseo_breadcrumb_separator', '%DOMAIN_NAME%_yoast_breadcrumb_items', 10);
  }
  // Wrap breadcrumbs in styleable list items.
  function %DOMAIN_NAME%_yoast_breadcrumb_separator() {
    return '</li><li>';
  }
  add_filter('wpseo_breadcrumb_separator', '%DOMAIN_NAME%_yoast_breadcrumb_separator', 10);
  // Change metabox priority to show it at the bottom of the page.
  function %DOMAIN_NAME%_yoast_metabox_priority() {
    return 'low';
  }
  add_filter('wpseo_metabox_prio', '%DOMAIN_NAME%_yoast_metabox_priority');
}

/**
 * Load the Custom Header feature.
 */
require get_template_directory() .'/inc/custom-header.php';

/**
 * Load customizer additions.
 */
require get_template_directory() .'/inc/customizer.php';

/**
 * Load custom post types and taxonomies.
 */
require get_template_directory() .'/inc/custom-types.php';
require get_template_directory() .'/inc/custom-taxonomies.php';

/**
 * Load custom widgets.
 */
require get_template_directory() .'/inc/custom-widgets.php';

/**
 * Load ACF integrations.
 */
if ( class_exists('ACF') ) {
  require get_template_directory() .'/inc/custom-acf.php';
}

/**
 * Load WooCommerce integrations.
 */
if ( class_exists('WooCommerce') ) {
  require get_template_directory() .'/inc/woocommerce.php';
}

/**
 * Load WPML integrations.
 */
if ( class_exists('SitePress') ) {
  require get_template_directory() .'/inc/custom-wpml.php';
}
