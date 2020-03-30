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
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="profile" href="https://gmpg.org/xfn/11">

  <!-- <link rel="shortcut icon" href="<?= get_template_directory_uri() ?>/favicon.ico" />
  <link rel="apple-touch-icon" href="<?= get_template_directory_uri() ?>/assets/img/apple-icon.png" />
  <link rel="apple-touch-icon-precomposed" href="<?= get_template_directory_uri() ?>/assets/img/apple-icon-precomposed.png" />
  <link rel="icon" type="image/png" href="<?= get_template_directory_uri() ?>/assets/img/favicon-16x16.png" sizes="16x16">
  <link rel="icon" type="image/png" href="<?= get_template_directory_uri() ?>/assets/img/favicon-32x32.png" sizes="32x32">
  <link rel="icon" type="image/png" href="<?= get_template_directory_uri() ?>/assets/img/favicon-96x96.png" sizes="96x96"> -->

	<?php wp_head(); ?>

</head>
<body <?php body_class(); ?>>
  <header id="header">
    <?php
      /**
       * Header content here.
       */
    ?>
  </header>
  <main id="main-content">
