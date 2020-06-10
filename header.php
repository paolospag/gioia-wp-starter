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
    <?php
      /**
       * Header content here.
       *
       * @example
       * #header
       * |__.header__main
       *    |__.site-logo
       *    |__.navbar
       *       |__.navbar__nav
       *       |__.navbar__cart
       *       |__.navbar__search
       * |__.header__search
       *    |__.search-form
       */
    ?>
    <div class="<?= esc_attr('site-'.has_custom_logo() ? 'logo' : 'title'); ?>">
      <?php if( has_custom_logo() ): ?>
        <?php the_custom_logo(); ?>
      <?php else: ?>
        <a href="<?= esc_url( home_url('/') ); ?>">
          <?php bloginfo('name'); ?>
        </a>
      <?php endif; ?>
    </div>
  </header>
  <main id="main-content">
