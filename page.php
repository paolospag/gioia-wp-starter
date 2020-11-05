<?php
/**
 * The template for displaying page.
 *
 * This is the template that displays all pages by default.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package %DOMAIN_NAME%
 */
get_header();
?>

<div class="container">
  <?php
    while( have_posts() ): the_post();
      the_content();
    endwhile;
  ?>
</div>

<?php
get_footer();
