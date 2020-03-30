<?php
/**
 * The theme sidebar
 *
 * It contains widgets or complementary content
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package %DOMAIN_NAME%
 */
$classes = 'col-md-3 col-lg-3 offset-lg-1';
?>
<aside id="sidebar" class="<?= $classes ?>">
  <?php
    if( is_active_sidebar('sidebar') ) {
      dynamic_sidebar('sidebar');
    }
  ?>
</aside>
