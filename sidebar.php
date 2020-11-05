<?php
/**
 * The theme sidebar.
 *
 * It contains widgets or complementary content.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package %DOMAIN_NAME%
 */
?>

<aside id="sidebar" class="col-sm-3 col-xl-3 offset-xl-1">
  <?php
    if( is_active_sidebar('sidebar') ) {
      dynamic_sidebar('sidebar');
    }
  ?>
</aside>
