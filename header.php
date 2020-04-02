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

  <?php wp_head(); ?>

</head>
<body <?php body_class(); ?>>
  <?php wp_body_open(); ?>
  <header id="header">
    <?php
      /**
       * Header content here.
       */
    ?>
  </header>
  <main id="main-content">
