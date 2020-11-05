<?php
/**
 * The theme header.
 *
 * This is the template that displays all of the <head> section and everything up until <main id="main-content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package %DOMAIN_NAME%
 */
$show_cart_button = get_theme_mod('show_cart_button', false);
$show_search_button = get_theme_mod('show_search_button', false);
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="profile" href="https://gmpg.org/xfn/11">

  <?php wp_head(); ?>

  <?php if( $gtag_ua_code = get_theme_mod('gtag_ua_code', false) ): ?>
    <!-- Global Site Tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=<?= $gtag_ua_code; ?>"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
      gtag('config', '<?= $gtag_ua_code; ?>');
    </script>
  <?php endif; ?>

</head>
<body <?php body_class(); ?>>
  <?php wp_body_open(); ?>
  <header id="header">
    <div class="header__main">
      <?php get_template_part('template-parts/partials/site', 'logo'); ?>
      <nav class="navbar<?php if($show_search_button){echo ' navbar--search';} ?>">
        <?php
          wp_nav_menu([
            // 'depth' => 2,
            'container' => false,
            'menu_id' => 'main-menu',
            'menu_class' => 'navbar__nav',
            'theme_location' => 'primary',
            'fallback_cb' => '__return_false'
          ]);
          get_template_part('template-parts/partials/navbar', 'toggle');
          if( $show_cart_button && function_exists('%DOMAIN_NAME%_woocommerce_cart_button') ) {
            %DOMAIN_NAME%_woocommerce_cart_button();
          }
          if( $show_search_button ) {
            get_template_part('template-parts/partials/navbar', 'search');
          }
        ?>
      </nav>
    </div>
    <?php if( $show_search_button ): ?>
      <div class="header__search">
        <?php get_search_form(['has_dismiss'=> true]); ?>
      </div>
    <?php endif; ?>
  </header>
  <main id="main-content">
